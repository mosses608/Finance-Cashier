<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfomaOutStore extends Model
{
    //
    protected $table = 'profoma_out_store';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = [
        'product_name',
        'customer_id',
        'order_status',
        'quantity',
        'amountPay',
        'discount',
        'soft_delete',
    ];
}
