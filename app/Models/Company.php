<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    //
    protected $table = 'companies';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = [
        'company_reg_no',
        'tin',
        'vrn',
        'company_name',
        'company_email',
        'website',
        'region',
        'address',
        'logo',
        'soft_delete',
        'status',
    ];
}
