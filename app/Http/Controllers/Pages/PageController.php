<?php

namespace App\Http\Controllers\Pages;

use Carbon\Carbon;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PageController extends Controller
{
    //
    public function welcome(){
        return view('welcome');
    }

    public function dashboard(){
        $todayDate = Carbon::now()->format('Y-m-d');

        $totalAmount = 0;
        $todayTransactions = Transaction::whereDate('created_at', $todayDate)->get();

        foreach ($todayTransactions as $td) {
            $totalAmount += $td->selling_price;
        }

        $lastFiveTransactions = Transaction::orderBy('id','desc')->paginate(4);
        $transactions = Transaction::orderBy('created_at','asc')->get();
        return view('inc.dashboard',
         compact(
            'transactions',
            'lastFiveTransactions',
            'todayDate',
            'todayTransactions',
            'totalAmount',
        ));
    }
}
