<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRoles extends Model
{
    //
    protected $table = 'user_roles';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = [
        'name',
    ];
}
