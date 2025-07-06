<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalaryAdvance extends Model
{
    //
    protected $table = 'salary_advances';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = [
        'date','amount','staff_id','year','project',
        'attachment','description','status','approved_by',
        'approved_at','paid','payment_date','month','soft_delete','created_by'
    ];
}
