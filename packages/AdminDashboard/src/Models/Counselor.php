<?php

namespace AdminDashboard\Models;

use Illuminate\Database\Eloquent\Model;

class Counselor extends Model
{
    protected $fillable = ['name', 'qualification', 'email', 'status'];
}