<?php

namespace Submissions\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Submissions\Models\Submission;

class SubmissionController extends Controller
{
    public function index(Request $request)
    {
        $author = $this->currentAuthor($request);
        if ($author === null) {
            return $this->signInRequired();
        }

        [$role, $name] = $author;

        $submissions = Submission::where('author_role', $role)
            ->where('author_name', $name)
            ->latest()
            ->get();

        return view('submissions::index', compact('submissions'));
    }

    public function create(Request $request)
    {
        if ($this->currentAuthor($request) === null) {
            return $this->signInRequired();
        }

        return view('submissions::create');
    }

    public function store(Request $request)
    {
        $author = $this->currentAuthor($request);
        if ($author === null) {
            return redirect()->route('submissions.create');
        }

        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'abstract'    => 'nullable|string',
            'content'     => 'nullable|string',
            'paper_file'  => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'visibility'  => 'required|in:public,private',
            'access_type' => 'required|in:free,paid',
            'price'       => 'nullable|required_if:access_type,paid|numeric|min:0',
        ]);

        if (empty($data['content']) && !$request->hasFile('paper_file')) {
            return back()->withInput()->withErrors('Please either write your content or upload a paper file.');
        }

        [$role, $name] = $author;

        if ($request->hasFile('paper_file')) {
            $data['file_path'] = $request->file('paper_file')->store('submissions', 'public');
        }

        $data['slug'] = $this->generateUniqueSlug($data['title']);

        unset($data['paper_file']);

        Submission::create([
            ...$data,
            'author_role'  => $role,
            'author_name'  => $name,
            'status'       => 'submitted',
            'submitted_at' => now(),
        ]);

        return redirect()->route('submissions.index')
            ->with('success', 'Your submission has been received and is pending review.');
    }

    private function generateUniqueSlug(string $title): string
    {
        $base = \Illuminate\Support\Str::slug($title);
        $slug = $base;
        $i = 1;
        while (Submission::where('slug', $slug)->exists()) {
            $slug = "{$base}-" . ++$i;
        }
        return $slug;
    }

    public function show(Request $request, Submission $submission)
    {
        $author = $this->currentAuthor($request);
        if ($author === null) {
            return $this->signInRequired(403);
        }

        [$role, $name] = $author;

        abort_unless($submission->author_role === $role && $submission->author_name === $name, 403);

        $submission->load('reviews');

        return view('submissions::show', compact('submission'));
    }

    private function signInRequired(int $status = 200)
    {
        return response()->view('submissions::sign-in', [
            'isStaff' => (bool) session('staff_role'),
        ], $status);
    }

    /**
     * TODO: replace with real auth (auth()->user()) once unified login exists.
     * Reads whichever session role is currently active and returns [role, name].
     */
    private function currentAuthor(Request $request): ?array
    {
        if (session('counselor_logged_in')) {
            return ['counselor', session('counselor_name', 'Counselor')];
        }
        if (session('client_logged_in')) {
            return ['client', session('client_profile.username', 'Client User')];
        }
        if (session('parent_logged_in')) {
            return ['parent', session('parent_profile.username', 'Parent User')];
        }

        return null;
    }
}