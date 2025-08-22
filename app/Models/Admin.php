<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $table = 'admins';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = [
        'first_name',
        'last_name',
        'phone',
        'email',
        'gender',
        'photo',
        'soft_delete',
    ];
}
