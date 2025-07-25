<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expenses extends Model
{
    //
    protected $table = 'expenses';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = [
        'expense_name',
        'amount',
        'company_id',
        'expense_type',
        'budget_id',
        'reference_no',
        'description',
        'expense_date',
        'created_by',
        'soft_delete',
    ];
}
