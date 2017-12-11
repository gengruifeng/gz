<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

class UserEducations extends Model
{
    //
    protected $table='user_educations';

    protected $fillable = ['uid', 'enrolled', 'graduated', 'school', 'department'];
}
