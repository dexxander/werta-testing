<?php

namespace Submissions\Models;

use Illuminate\Database\Eloquent\Model;
use AdminDashboard\Models\Administrator;

class SubmissionReview extends Model
{
    protected $fillable = [
        'submission_id', 'reviewer_id', 'status',
        'comments', 'suggestions', 'recommendation', 'score', 'submitted_at',
    ];

    public function submission()
    {
        return $this->belongsTo(Submission::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(Administrator::class, 'reviewer_id');
    }
}