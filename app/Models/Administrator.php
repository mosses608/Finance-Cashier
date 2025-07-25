<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Administrator extends Model
{
    //
    protected $table = 'administrators';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = [
        'company_id',
        'names',
        'role_id',
        'email',
        'phone'
    ];
}
