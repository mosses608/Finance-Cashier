<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    //
    public function scopeFilter($query, array $filters){
        if($filters['search'] ?? false){
            $query->where('from_account', 'like', '%' . request('search') . '%')
            ->orwhere('to_account', 'like', '%' . request('search') . '%');
        }
    }

    protected $fillable = [
        'staff_id',
        'from_account',
        'to_account',
        'amount',
        'note',
    ];
}
