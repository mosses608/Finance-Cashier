<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExpensesType extends Model
{
    //
    protected $table = 'expenses_type';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = [
        'name',
        'soft_delete',
    ];
}
