<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceStatus extends Model
{
    //
    protected $table = 'invoice_status';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $fillable = [
        'name',
    ];
}
