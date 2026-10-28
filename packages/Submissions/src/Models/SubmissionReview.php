<?php

namespace Submissions\Models;

use AdminDashboard\Models\Reviewer;
use Illuminate\Database\Eloquent\Model;

class SubmissionReview extends Model
{
    /** Evaluation criteria, each scored 1–5. Column => label. */
    public const CRITERIA = [
        'score_accuracy'    => 'Accuracy and evidence',
        'score_originality' => 'Originality',
        'score_clarity'     => 'Clarity and structure',
        'score_relevance'   => 'Relevance to students and families',
        'score_ethics'      => 'Ethics and safety',
    ];

    public const RECOMMENDATIONS = [
        'accept'         => 'Accept',
        'minor_revision' => 'Minor revisions',
        'major_revision' => 'Major revisions',
        'reject'         => 'Reject',
    ];

    protected $fillable = [
        'submission_id', 'reviewer_id', 'status',
        'score_accuracy', 'score_originality', 'score_clarity', 'score_relevance', 'score_ethics',
        'recommendation', 'suitable_for_sale', 'comments', 'suggestions', 'confidential_notes',
        'conflict_of_interest', 'show_on_article', 'assigned_at', 'due_at', 'submitted_at',
    ];

    protected $casts = [
        'suitable_for_sale'    => 'boolean',
        'conflict_of_interest' => 'boolean',
        'show_on_article'      => 'boolean',
        'assigned_at'          => 'datetime',
        'due_at'               => 'datetime',
        'submitted_at'         => 'datetime',
    ];

    public function submission()
    {
        return $this->belongsTo(Submission::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(Reviewer::class, 'reviewer_id');
    }

    /** Average of the criteria scores (1–5), or null before the review is done. */
    public function overallScore(): ?float
    {
        $scores = array_filter(array_map(fn ($c) => $this->{$c}, array_keys(self::CRITERIA)), fn ($v) => $v !== null);

        return $scores ? round(array_sum($scores) / count($scores), 1) : null;
    }

    public function recommendationLabel(): ?string
    {
        return self::RECOMMENDATIONS[$this->recommendation] ?? null;
    }

    /** Days between assignment and completion. */
    public function turnaroundDays(): ?int
    {
        return $this->submitted_at && $this->assigned_at
            ? (int) max(0, $this->assigned_at->diffInDays($this->submitted_at))
            : null;
    }
}
