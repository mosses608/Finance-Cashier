<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuthUserModule extends Model
{
    protected $table = 'auth_user_modules';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = [
        'module_name',
        'module_label',
        'module_path',
        'module_parent_id',
        'module_icon',
        'soft_delete',
    ];
}
