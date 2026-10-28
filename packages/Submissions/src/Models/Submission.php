<?php

namespace Submissions\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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

    /** Article categories. Keys are stored in submissions.category and used in ?category= filters. */
    public const CATEGORIES = [
        'science'       => ['label' => 'Science',            'icon' => 'bi-lightbulb'],
        'social'        => ['label' => 'Social Sciences',    'icon' => 'bi-people'],
        'education'     => ['label' => 'Education',          'icon' => 'bi-mortarboard'],
        'mental-health' => ['label' => 'Mental Health',      'icon' => 'bi-heart-pulse'],
        'parenting'     => ['label' => 'Parenting & Family', 'icon' => 'bi-house-heart'],
        'career'        => ['label' => 'Career',             'icon' => 'bi-briefcase'],
    ];

    protected $fillable = [
        'title', 'slug', 'category', 'abstract', 'content', 'file_path',
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

    /** Completed reviews whose reviewer agreed to be named on the public article. */
    public function publicReviews()
    {
        return $this->reviews()
            ->where('status', 'completed')
            ->where('show_on_article', true)
            ->whereHas('reviewer', fn ($q) => $q->where('status', 'approved')->where('show_publicly', true))
            ->with('reviewer');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')->where('visibility', 'public');
    }

    public function getCategoryLabelAttribute(): ?string
    {
        return self::CATEGORIES[$this->category]['label'] ?? null;
    }

    public function getCategoryIconAttribute(): string
    {
        return self::CATEGORIES[$this->category]['icon'] ?? 'bi-journal-text';
    }

    public function getIsPremiumAttribute(): bool
    {
        return $this->access_type === 'paid';
    }

    /** Public author page slug parts: /authors/{role}/{name-slug} */
    public function getAuthorUrlAttribute(): string
    {
        return route('public.authors.show', ['role' => $this->author_role, 'author' => Str::slug($this->author_name)]);
    }

    public static function categoryLabel(?string $key): ?string
    {
        return self::CATEGORIES[$key]['label'] ?? null;
    }
}
