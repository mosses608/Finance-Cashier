<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Allowance extends Model
{
    protected $table = 'allowance';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = [
        'name',
        'default_amount',
        'company_id',
        'created_by',
        'soft_delete',
    ];
}
