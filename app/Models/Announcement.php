<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Announcement extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'body',
    ];

    // 投稿者（ユーザ）とのリレーション
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
