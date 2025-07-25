<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleMgt extends Model
{
    //
    protected $table = 'sales';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = [
        'user_id',
        'invoice_id',
        'tax',
        'balance',
        'amount_paid',
        'payment_method',
        'is_paid',
        'status',
        'notes',
        'company_id',
        'soft_delete',
    ];
}
