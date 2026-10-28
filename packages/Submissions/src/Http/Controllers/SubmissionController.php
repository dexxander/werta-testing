<?php

namespace Submissions\Http\Controllers;

use App\Support\DemoAuth;
use Articles\Support\MockBilling;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Submissions\Models\Submission;

class SubmissionController extends Controller
{
    /** Tab => statuses shown on the Your Articles page. */
    private const TABS = [
        'all'       => null,
        'review'    => ['submitted', 'under_review'],
        'action'    => ['revisions_requested', 'accepted'],
        'published' => ['published'],
        'rejected'  => ['rejected'],
    ];

    public function index(Request $request)
    {
        $author = $this->currentAuthor();
        if ($author === null) {
            return $this->signInRequired();
        }

        [$role, $name] = $author;

        $all = Submission::where('author_role', $role)
            ->where('author_name', $name)
            ->latest('submitted_at')
            ->get();

        $tab = array_key_exists($request->query('tab'), self::TABS) ? $request->query('tab') : 'all';
        $submissions = self::TABS[$tab] ? $all->whereIn('status', self::TABS[$tab])->values() : $all;

        $counts = collect(self::TABS)->map(fn ($statuses) => $statuses ? $all->whereIn('status', $statuses)->count() : $all->count());
        // "Needs action" = revisions requested, or accepted premium waiting for the publish fee.
        $needsAction = $all->filter(fn ($s) => $s->status === 'revisions_requested' || ($s->status === 'accepted' && $s->access_type === 'paid'))->count();
        $counts['action'] = $needsAction;
        if ($tab === 'action') {
            $submissions = $all->filter(fn ($s) => $s->status === 'revisions_requested' || ($s->status === 'accepted' && $s->access_type === 'paid'))->values();
        }

        $copies = $all->sum(fn ($s) => MockBilling::totalSales($s));
        $hasPublished = $all->where('status', 'published')->where('visibility', 'public')->isNotEmpty();
        $authorUrl = $hasPublished ? route('public.authors.show', ['role' => $role, 'author' => Str::slug($name)]) : null;

        return view('submissions::index', compact('submissions', 'tab', 'counts', 'copies', 'authorUrl'));
    }

    public function create(Request $request)
    {
        if ($this->currentAuthor() === null) {
            return $this->signInRequired();
        }

        return view('submissions::create');
    }

    public function store(Request $request)
    {
        $author = $this->currentAuthor();
        if ($author === null) {
            return redirect()->route('submissions.create');
        }

        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'category'    => 'required|in:' . implode(',', array_keys(Submission::CATEGORIES)),
            'abstract'    => 'nullable|string',
            'content'     => 'nullable|string',
            'paper_file'  => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'visibility'  => 'required|in:public,private',
            'access_type' => 'required|in:free,paid',
        ]);

        if (empty($data['content']) && ! $request->hasFile('paper_file')) {
            return back()->withInput()->withErrors('Please either write your content or upload a paper file.');
        }

        [$role, $name] = $author;

        if ($request->hasFile('paper_file')) {
            $data['file_path'] = $request->file('paper_file')->store('submissions', 'public');
        }

        $data['slug'] = $this->generateUniqueSlug($data['title']);
        // Every premium paper has the same fixed price (stored as a snapshot).
        $data['price'] = $data['access_type'] === 'paid' ? MockBilling::articlePriceRm() : null;

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
        $base = Str::slug($title);
        $slug = $base;
        $i = 1;
        while (Submission::where('slug', $slug)->exists()) {
            $slug = "{$base}-" . ++$i;
        }

        return $slug;
    }

    public function show(Request $request, Submission $submission)
    {
        $author = $this->currentAuthor();
        if ($author === null) {
            return $this->signInRequired(403);
        }

        [$role, $name] = $author;

        abort_unless($submission->author_role === $role && $submission->author_name === $name, 403);

        $reviews = $submission->reviews()->with('reviewer')->where('status', 'completed')->orderBy('submitted_at')->get();
        $pendingReviews = $submission->reviews()->where('status', 'assigned')->count();
        $copies = MockBilling::totalSales($submission);

        return view('submissions::show', compact('submission', 'reviews', 'pendingReviews', 'copies'));
    }

    /** DEMO: author pays the RM 80 publish fee for an accepted premium paper. */
    public function payFee(Submission $submission)
    {
        $err = MockBilling::payPublishFee($submission);

        return $err
            ? back()->with('mock_error', $err)
            : redirect()->route('submissions.index', ['tab' => 'published'])
                ->with('mock_ok', __('Publish fee paid (demo). “:title” is now live.', ['title' => $submission->title]));
    }

    private function signInRequired(int $status = 200)
    {
        return response()->view('submissions::sign-in', [
            'isStaff' => (bool) session('staff_role'),
        ], $status);
    }

    /** TODO: replace with auth()->user() once unified login exists. */
    private function currentAuthor(): ?array
    {
        return DemoAuth::current();
    }
}
