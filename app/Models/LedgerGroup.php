<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LedgerGroup extends Model
{
    //
    protected $fillable = [
        'group_type',
        'group_name',
    ];
}
