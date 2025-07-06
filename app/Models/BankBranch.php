<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankBranch extends Model
{
    //
    protected $table = 'bank_branch';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = [
        'bank_name',
        'branch_name',
        'branch_code',
        'added_by',
        'soft_delete',
    ];
}
