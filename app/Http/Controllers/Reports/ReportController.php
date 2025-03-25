<?php

namespace App\Http\Controllers\Reports;

use App\Models\Ledger;
use App\Models\Journal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReportController extends Controller
{
    //
    public function trial_balance(Request $request){
        $ledgers = Ledger::all();
        $journals = Journal::orderBy('id', 'desc')->filter(request(['search']))->get();
        $debitBalanceJ = Journal::where('mode', 'Dr')->get();
        $creditBalanceJ = Journal::where('mode', 'Cr')->get();

        if($request->has(['fromDate','toDate']) && $request->fromDate && $request->toDate){
            $from = $request->fromDate;
            $to = $request->toDate;

            $journals = Journal::whereBetween('date', [$from, $to])->get();
        }
        return view('inc.trial-balance', compact('journals','ledgers','debitBalanceJ','creditBalanceJ'));
    }
}
