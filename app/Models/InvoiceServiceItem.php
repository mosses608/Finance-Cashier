<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceServiceItem extends Model
{
    //
    protected $table = 'invoive_service_items';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = [
        'invoice_id',
        'service_id',
        'amount',
        'quantity',
        'discount',
        'soft_delete',
    ];
}
