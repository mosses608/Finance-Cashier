<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    //
    public function scopeFilter($query, array $filters){
        if($filters['search'] ?? false){
            $query->where('bank_name', 'like', '%' . request('search') . '%')
            ->orwhere('account_no', 'like', '%' . request('search') . '%')
            ->orwhere('branch', 'like', '%' . request('search') . '%')
            ->orwhere('acc_holder', 'like', '%' . request('search') . '%')
            ->orwhere('phone', 'like', '%' . request('search') . '%');
        }
    }

    protected $fillable = [
        'bank_name',
        'branch',
        'account_name',
        'account_no',
        'acc_holder',
        'phone',
        'balance',
    ];
}
