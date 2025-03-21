<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ledger extends Model
{
    //
    public function scopeFilter($query, array $filters){
        if($filters['search'] ?? false){
            $query->where('date', 'like', '%' . request('search') . '%')
            ->orwhere('customer_name', 'like' , '%' . request('search') . '%')
            ->orwhere('ledger_type', 'like', '%' . request('search') . '%')
            ->orwhere('ledger_group', 'like', '%' . request('search') . '%')
            ->orwhere('mode', 'like' , '%' . request('search') . '%');
        }
    }

    protected $fillable = [
        'date',
        'customer_name',
        'ledger_type',
        'ledger_group',
        'mode',
        'amount',
    ];
}
