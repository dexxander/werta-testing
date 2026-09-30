<?php

namespace Submissions\Models;

use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
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