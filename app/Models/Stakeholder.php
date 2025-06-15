<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stakeholder extends Model
{
    //
    protected $table = 'stakeholders';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = [
        'name',
        'phone',
        'address',
        'email',
        'tin',
        'vrn',
        'region_id',
        'stakeholder_category',
        'customer_type',
        'identification_type',
        'identification_number',
        'customer_group',
        'regulator_type',
        'supplier_type',
        'soft_delete',
    ];
}
