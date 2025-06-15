<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StakeholderCategory extends Model
{
    //
    protected $table = 'stakeholder_category';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = [
        'name','soft_delete',
    ];
}
