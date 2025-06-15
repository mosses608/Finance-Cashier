<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockOutTransaction extends Model
{
    //
    protected $table = 'stock_out_transaction';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = [
        'user_id',
        'product_id',
        'stockout_quantity',
        'soft_delete',
    ];
}
