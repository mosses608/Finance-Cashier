<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    //
    protected $table = 'banks';
    protected $primaryKey = 'id';
    public $incrementing = true;
    
    protected $fillable = [
        'bank_name',
        'account_name',
        'phone',
        'account_number',
        'company_id',
        'address',
        'email',
        'box',
        'bank_code',
        'region',
        'soft_delete',
    ];
}
