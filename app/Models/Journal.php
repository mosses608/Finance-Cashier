<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Journal extends Model
{
    //

    public function scopeFilter($query, array $filters){    
        if($filters['search'] ?? false){
            $query->where('date', 'like' , '%' . request('search') . '%')
            ->orwhere('ledger_id', 'like' , '%' . request('search') . '%')
            ->orwhere('particular', 'like' , '%' . request('search') . '%')
            ->orwhere('mode', 'like', '%' . request('search') . '%');
        }
    }

    protected $fillable = [
        'date',
        'ledger_id',
        'particular',
        'mode',
    ];
}
