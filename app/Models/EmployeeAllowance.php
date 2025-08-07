<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeAllowance extends Model
{
    protected $table = 'employee_allowances';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = [
        'allowance_type_id',
        'budget_code_id',
        'amount',
        'soft_delete',
    ];
}
