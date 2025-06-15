<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IdentificationSource extends Model
{
    //
    protected $table = 'identification_source';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = [
        'slug','name','soft_delete',
    ];
}
