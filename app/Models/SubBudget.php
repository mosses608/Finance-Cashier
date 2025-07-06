<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubBudget extends Model
{
    //
    protected $table = 'sub_budgests';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = [
        'budget_name',
        'budget_code',
        'sub_budget_code',
        'sub_budget_description',
        'unit_cost',
        'quantity',
        'unit_meausre',
        'cost_type',
        'soft_delete',
    ];
}
