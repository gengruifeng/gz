<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    /**
     * The table associated with the entity
     *
     * @var string
     */
    protected $table = 'notifications';
    protected $fillable = [
        'from',
        'recipient',
        'type',
        'content',
        'show_type',
        'by',
        'associate_id',
        'read',
    ];
}
