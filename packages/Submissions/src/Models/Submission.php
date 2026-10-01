<?php

namespace Submissions\Models;

use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    public const STATUS_LABELS = [
        'submitted'           => ['label' => 'Waiting for Review', 'class' => 'bg-gray-100 text-gray-600'],
        'under_review'        => ['label' => 'Under Review',       'class' => 'bg-blue-50 text-blue-700'],
        'revisions_requested' => ['label' => 'Revisions Requested','class' => 'bg-yellow-50 text-yellow-700'],
        'accepted'            => ['label' => 'Accepted',           'class' => 'bg-green-50 text-green-700'],
        'rejected'            => ['label' => 'Not Approved',       'class' => 'bg-red-50 text-red-700'],
        'published'           => ['label' => 'Approved',           'class' => 'bg-green-50 text-green-700'],
    ];

    protected $fillable = [
        'title', 'slug', 'abstract', 'content', 'file_path',
        'author_role', 'author_name', 'status', 'decision_notes', 'submitted_at',
        'visibility', 'access_type', 'price',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
    ];

    public function reviews()
    {
        return $this->hasMany(SubmissionReview::class);
    }
}