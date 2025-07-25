<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    //
    protected $table = 'service';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = [
        'name',
        'description',
        'price',
        'category',
        'quantity',
        'company_id',
        'active',
        'soft_delete',
        'created_by',
    ];
}
