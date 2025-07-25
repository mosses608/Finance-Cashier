<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    //
    protected $table = 'subscriptions';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = [
        'email',
        'soft_delete',
    ];
}
