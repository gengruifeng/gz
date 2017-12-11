<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

class UserProficiencies extends Model
{
    //
    protected $table='user_proficiencies';

    protected $fillable = ['uid', 'tag_id'];
}
