<?php

namespace AdminDashboard\Models;

use Illuminate\Database\Eloquent\Model;
use Submissions\Models\Submission;
use Submissions\Models\SubmissionReview;

/** A verified professional who evaluates manuscripts before publication. */
class Reviewer extends Model
{
    protected $fillable = [
        'name', 'slug', 'title', 'field', 'institution', 'qualifications', 'registration_no',
        'email', 'bio', 'proof_path', 'status', 'show_publicly', 'approved_at',
    ];

    protected $casts = [
        'show_publicly' => 'boolean',
        'approved_at'   => 'datetime',
    ];

    public function reviews()
    {
        return $this->hasMany(SubmissionReview::class, 'reviewer_id');
    }

    public function getFieldLabelAttribute(): ?string
    {
        return Submission::categoryLabel($this->field);
    }

    public function getAvatarUrlAttribute(): string
    {
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=7B6B35&color=fff&size=128';
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }
}
