<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfomaInvoice extends Model
{
    //
    protected $table = 'profoma_invoice';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = [
        'invoice_id',
        'category_id',
        'invoice_item_id',
        'profoma_status',
        'soft_delete',
    ];
}
