<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

class UserFollowing extends Model
{
    /**
     * The table associated with the entity
     *
     * @var string
     */
    protected $table = 'user_following';
    protected $fillable = [
        'uid',
        'following',
    ];
}
