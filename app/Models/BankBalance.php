<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankBalance extends Model
{
    //
    protected $table = 'bank_balances';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = [
        'bank_id',
        'opening_balance',
        'current_balance',
        'allow_overdraft',
        'overdraft_limit',
        'as_of_date',
    ];
}
