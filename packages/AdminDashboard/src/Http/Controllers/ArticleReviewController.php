<?php

namespace AdminDashboard\Http\Controllers;

use AdminDashboard\Models\Reviewer;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Submissions\Models\Submission;
use Submissions\Models\SubmissionReview;

/**
 * Editorial workflow: assign verified reviewers, read their evaluations,
 * make the final decision, and publish free papers.
 * Premium papers go live once the author pays the publish fee.
 */
class ArticleReviewController extends Controller
{
    private const FILTERS = [
        'open'      => ['submitted', 'under_review'],
        'decided'   => ['revisions_requested', 'accepted', 'rejected'],
        'published' => ['published'],
    ];

    public function index(string $role, Request $request)
    {
        $filter = array_key_exists($request->query('status'), self::FILTERS) ? $request->query('status') : 'open';

        $submissions = Submission::with(['reviews.reviewer'])
            ->whereIn('status', self::FILTERS[$filter])
            ->orderByRaw("case status when 'submitted' then 0 when 'under_review' then 1 else 2 end")
            ->latest('submitted_at')
            ->get();

        $counts = collect(self::FILTERS)->map(fn ($statuses) => Submission::whereIn('status', $statuses)->count());
        $reviewers = Reviewer::approved()->orderBy('name')->get();
        $selected = $submissions->firstWhere('id', (int) $request->query('open')) ?? $submissions->first();

        return view('admindashboard::article-reviews', compact('role', 'submissions', 'counts', 'filter', 'reviewers', 'selected'));
    }

    public function assign(string $role, Request $request, Submission $submission)
    {
        $data = $request->validate([
            'reviewer_id' => 'required|exists:reviewers,id',
            'due_days'    => 'nullable|integer|between:3,30',
        ]);
        $reviewer = Reviewer::approved()->findOrFail($data['reviewer_id']);

        $review = SubmissionReview::firstOrNew(['submission_id' => $submission->id, 'reviewer_id' => $reviewer->id]);
        if ($review->exists && $review->status !== 'declined') {
            return back()->with('success', __(':name is already assigned to this paper.', ['name' => $reviewer->name]));
        }
        $review->fill([
            'status' => 'assigned', 'assigned_at' => now(), 'due_at' => now()->addDays((int) ($data['due_days'] ?? 14)),
            'submitted_at' => null, 'show_on_article' => true, 'conflict_of_interest' => false,
        ])->save();

        if ($submission->status === 'submitted') {
            $submission->update(['status' => 'under_review']);
        }

        return redirect(url("/{$role}/reviews?open={$submission->id}"))
            ->with('success', __(':name was assigned to review “:title”.', ['name' => $reviewer->name, 'title' => $submission->title]));
    }

    public function decide(string $role, Request $request, Submission $submission)
    {
        $data = $request->validate([
            'decision'    => 'required|in:accept,revisions,reject',
            'access_type' => 'required|in:free,paid',
            'notes'       => 'nullable|string|max:2000',
        ]);

        $status = ['accept' => 'accepted', 'revisions' => 'revisions_requested', 'reject' => 'rejected'][$data['decision']];
        $submission->update([
            'status' => $status,
            'access_type' => $data['access_type'],
            'price' => $data['access_type'] === 'paid' ? \Articles\Support\MockBilling::articlePriceRm() : null,
            'decision_notes' => $data['notes'] ?: null,
        ]);

        $msg = match (true) {
            $status === 'accepted' && $data['access_type'] === 'paid' => __('Accepted as premium. The author can now pay the publish fee.'),
            $status === 'accepted' => __('Accepted as a free article. Publish it when ready.'),
            $status === 'revisions_requested' => __('Revisions requested. The author can see the reviewers’ feedback.'),
            default => __('Paper not approved.'),
        };

        return redirect(url("/{$role}/reviews?status=decided&open={$submission->id}"))->with('success', $msg);
    }

    /** Free accepted papers can be published straight away by an editor. */
    public function publish(string $role, Submission $submission)
    {
        abort_unless($submission->status === 'accepted' && $submission->access_type === 'free', 422);
        $submission->update(['status' => 'published']);

        return redirect(url("/{$role}/reviews?status=published&open={$submission->id}"))
            ->with('success', __('“:title” is now live.', ['title' => $submission->title]));
    }
}
