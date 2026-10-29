<?php

namespace CommunityForum\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CommunityPost extends Model
{
    protected $fillable = ['topic', 'title', 'body', 'author_name'];

    public function replies(): HasMany
    {
        return $this->hasMany(CommunityReply::class);
    }
}
