<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    //
    protected $table = 'projects';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = [
        'name','soft_delete','company_id',
    ];
}
