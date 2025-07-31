<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankTransaction extends Model
{
    protected $table = 'bank_transactions';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = [
        'bank_id',
        'date',
        'amount',
        'type',
        'description',
        'reference_no',
        'related_module',
    ];
}
