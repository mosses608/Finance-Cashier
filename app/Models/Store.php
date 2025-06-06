<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    //
    protected $fillable = [
        'store_name',
        'city',
        'location',
        'store_keeper',
        'phone',
        'soft_delete',
    ];
}
