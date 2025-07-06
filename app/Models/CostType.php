<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CostType extends Model
{
    //
    protected $table = 'cost_types';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = [
        'name',
        'soft_delete',
    ];
}
