<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

class UserWork extends Model
{
    //
    protected $table='user_works';

    protected $fillable = ['uid', 'from', 'to', 'company', 'position'];
}
