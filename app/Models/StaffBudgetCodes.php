<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffBudgetCodes extends Model
{
    //
    protected $table = 'staff_budget_codes';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = [
        'project_name',
        'budget_code',
        'budget_year',
        'budget_name',
        'staff_id',
        'sub_budget_code',
        'created_by',
        'budget_cost',
        'soft_delete',
    ];
}
