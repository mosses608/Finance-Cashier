<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    //
    protected $table = 'budgets';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = [
        'budget_year',
        'currency',
        'cost_type',
        'budget_name',
        'budget_code',
        'project_name',
        'created_by',
        'soft_delete',
        'is_approved',
        'approved_by'
    ];
}
