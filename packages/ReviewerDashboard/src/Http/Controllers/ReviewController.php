<?php

namespace ReviewerDashboard\Http\Controllers;

use AdminDashboard\Models\Reviewer;
use App\Support\DemoAuth;
use Articles\Support\DemoStats;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Submissions\Models\SubmissionReview;

class ReviewController extends Controller
{
    /** Proposed honorarium per completed review (sen). Shown on the dashboard only. */
    public const REVIEW_FEE = 4000;

    public function dashboard()
    {
        if (! $reviewer = DemoAuth::reviewer()) {
            return redirect()->route('reviewer.login');
        }

        $queue = $reviewer->reviews()->with('submission')->where('status', 'assigned')->orderBy('due_at')->get();
        $completed = $reviewer->reviews()->with('submission')->where('status', 'completed')->latest('submitted_at')->get();

        $stats = [
            'assigned'  => $queue->count(),
            'dueSoon'   => $queue->filter(fn ($r) => $r->due_at && $r->due_at->lte(now()->addDays(7)))->count(),
            'completed' => $completed->count(),
            'avgDays'   => round((float) $completed->avg(fn ($r) => $r->turnaroundDays()), 1),
            'earned'    => $completed->count() * self::REVIEW_FEE,
        ];

        // Reviews completed per month (real rows, by completion month).
        $months = array_fill_keys(DemoStats::monthLabels(), 0);
        foreach ($completed as $r) {
            $label = $r->submitted_at?->translatedFormat('M');
            if ($label !== null && array_key_exists($label, $months) && $r->submitted_at->gte(now()->startOfMonth()->subMonths(5))) {
                $months[$label]++;
            }
        }

        return view('reviewerdashboard::dashboard', compact('reviewer', 'queue', 'completed', 'stats', 'months'));
    }

    public function completed()
    {
        if (! $reviewer = DemoAuth::reviewer()) {
            return redirect()->route('reviewer.login');
        }
        $reviews = $reviewer->reviews()->with('submission')->whereIn('status', ['completed', 'declined'])->latest('updated_at')->get();

        return view('reviewerdashboard::completed', compact('reviewer', 'reviews'));
    }

    public function guidelines()
    {
        if (! $reviewer = DemoAuth::reviewer()) {
            return redirect()->route('reviewer.login');
        }

        return view('reviewerdashboard::guidelines', compact('reviewer'));
    }

    public function show(SubmissionReview $review)
    {
        $reviewer = $this->authorize($review);
        if (! $reviewer) {
            return redirect()->route('reviewer.login');
        }
        $review->load('submission');

        return view('reviewerdashboard::review', compact('reviewer', 'review'));
    }

    public function submit(Request $request, SubmissionReview $review)
    {
        if (! $this->authorize($review)) {
            return redirect()->route('reviewer.login');
        }
        if ($review->status !== 'assigned') {
            return back()->with('mock_error', __('This review has already been submitted.'));
        }

        $rules = [
            'recommendation' => 'required|in:' . implode(',', array_keys(SubmissionReview::RECOMMENDATIONS)),
            'comments'       => 'required|string|max:3000',
            'suggestions'    => 'nullable|string|max:3000',
            'confidential_notes' => 'nullable|string|max:2000',
            'no_conflict'    => 'accepted',
            'show_on_article' => 'nullable|boolean',
        ];
        foreach (array_keys(SubmissionReview::CRITERIA) as $col) {
            $rules[$col] = 'required|integer|between:1,5';
        }
        if ($review->submission->access_type === 'paid') {
            $rules['suitable_for_sale'] = 'required|boolean';
        }
        $data = $request->validate($rules);

        $review->update([
            ...collect($data)->only(array_keys(SubmissionReview::CRITERIA))->all(),
            'recommendation'       => $data['recommendation'],
            'suitable_for_sale'    => $review->submission->access_type === 'paid' ? (bool) $data['suitable_for_sale'] : null,
            'comments'             => $data['comments'],
            'suggestions'          => $data['suggestions'] ?? null,
            'confidential_notes'   => $data['confidential_notes'] ?? null,
            'conflict_of_interest' => false,
            'show_on_article'      => $request->boolean('show_on_article'),
            'status'               => 'completed',
            'submitted_at'         => now(),
        ]);

        return redirect()->route('reviewer.dashboard')
            ->with('mock_ok', __('Evaluation submitted for “:title”. The editor will make the final decision.', ['title' => $review->submission->title]));
    }

    /** Decline, for example because of a conflict of interest. */
    public function decline(Request $request, SubmissionReview $review)
    {
        if (! $this->authorize($review)) {
            return redirect()->route('reviewer.login');
        }
        if ($review->status === 'assigned') {
            $review->update([
                'status' => 'declined',
                'conflict_of_interest' => $request->boolean('conflict'),
                'confidential_notes' => $request->input('reason'),
                'submitted_at' => now(),
            ]);
        }

        return redirect()->route('reviewer.dashboard')->with('mock_ok', __('Review declined. The editor will assign someone else.'));
    }

    /** The signed-in reviewer, if this review belongs to them. */
    private function authorize(SubmissionReview $review): ?Reviewer
    {
        $reviewer = DemoAuth::reviewer();
        if (! $reviewer) {
            return null;
        }
        abort_unless($review->reviewer_id === $reviewer->id, 403);

        return $reviewer;
    }
}
