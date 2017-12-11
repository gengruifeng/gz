<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

class UserProfiles extends Model
{
    //
    protected $table='user_profiles';

    protected $fillable = ['uid', 'summary', 'slogan'];

}
