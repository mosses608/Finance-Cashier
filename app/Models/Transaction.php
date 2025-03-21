<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    //
    protected $fillable = [
        'product_item_id',
        'stockout_quantity',
        'customer_name',
        'selling_price',
        'stock_out_mode',
        'user_id',
    ];
}
