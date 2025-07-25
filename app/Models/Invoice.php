<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    //
    protected $table = 'invoice';
    protected $primaryKey ='id';
    public $incrementing = true;

    protected $fillable = [
        'customer_id',
        'billId',
        'amount','status','soft_delete',
        'company_id',
    ];
}
