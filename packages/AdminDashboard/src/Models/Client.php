<?php

namespace AdminDashboard\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = ['name', 'email', 'path', 'status'];
}