<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    //
    protected $fillable = [
        'storage_item_id',
        'stock_code_combine',
        'remarks',
        'quantity_in',
        'quantity_out',
        'quantity_total',
        'item_price',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'storage_item_id', 'id');
    }

}
