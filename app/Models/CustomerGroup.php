<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerGroup extends Model
{
    //
    protected $table = 'customer_groups';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = [
        'name',
        'soft_delete',
    ];
}
