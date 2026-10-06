<?php

namespace AdminDashboard\Models;

use Illuminate\Database\Eloquent\Model;

class Administrator extends Model
{
    protected $fillable = ['name', 'email', 'status'];
}