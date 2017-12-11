<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    /**
     * The table associated with the entity
     *
     * @var string
     */
    protected $table = 'users';
    protected $fillable = [
        'mobile',
        'display_name',
        'email',
        'passcode',
        'avatar',
        'gender',
        'birthday',
        'province',
        'city',
        'occupation',
        'registered_ip',
        'disabled',
        'email_verified',
        'mobile_verified',
        'customized_uri',
        'remember_token',
    ];
}
