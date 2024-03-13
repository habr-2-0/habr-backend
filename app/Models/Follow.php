<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Follow extends Model
{
    use HasFactory;

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'follower_id',
        'following_id',
        'dateFollowed',
    ];

    /**
     * Get the follower user - the user who initiated the follow.
     */
    public function follower(): BelongsTo
    {
        return $this->belongsTo(User::class, 'follower_id');
    }

    /**
     * Get the following user - the user who is being followed.
     */
    public function following(): BelongsTo
    {
        return $this->belongsTo(User::class, 'following_id');
    }
}
