<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveAplication extends Model
{
    //
    protected $table = 'leave_applications';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = [
        'user_id',
        'leave_type',
        'start_date',
        'end_date',
        'reason',
        'status',
        'attachment',
        'is_adjusted',
        'adjusted_days',
        'is_adjustment_approved',
        'approved_by',
        'approved_at',
        'soft_delete',
    ];
}
