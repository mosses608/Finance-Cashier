<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    //
    protected $table = 'invoice_items';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = [
        'invoice_id',
        'item_id',
        'amount',
        'soft_delete',
    ];
}
