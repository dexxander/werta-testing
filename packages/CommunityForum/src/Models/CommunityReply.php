<?php

namespace CommunityForum\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommunityReply extends Model
{
    protected $fillable = ['body', 'author_name', 'parent_reply_id', 'owner_id'];

    protected $hidden = ['owner_id'];

    public function parentReply(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_reply_id');
    }
}
