<?php

namespace AdminDashboard\Models;

use Illuminate\Database\Eloquent\Model;

class Counselor extends Model
{
    protected $fillable = [
        'name',
        'qualification',
        'email',
        'status',
        'display_title',
        'specialties',
        'languages',
        'session_modes',
        'state',
        'years_experience',
        'bio',
        'rate_individual',
        'is_sample',
    ];

    protected $casts = [
        'specialties'   => 'array',
        'languages'     => 'array',
        'session_modes' => 'array',
        'is_sample'     => 'boolean',
    ];
}