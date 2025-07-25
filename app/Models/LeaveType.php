<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveType extends Model
{
    //
    protected $table = 'leave_types';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = [
        'name',
        'days',
        'company_id',
        'leave_priority',
        'gender_specification',
        'require_attachment',
        'is_balance_carry_over',
        'created_by',
        'soft_delete'
    ];
}
