<?php

namespace App\Http\Controllers\Vouchers;

use App\Models\Ledger;
use App\Models\Journal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VoucherController extends Controller
{
    //
    public function journals(Request $request){
        $ledgers = Ledger::orderBy('customer_name','asc')->get();
        $journals = Journal::orderBy('id','desc')->filter(request(['search']))->get();

        if($request->has(['fromDate', 'toDate']) && $request->fromDate && $request->toDate){
            $from = $request->fromDate;
            $to = $request->toDate;

            $journals = Journal::whereBetween('date', [$from, $to])->get();
        }
        return view('inc.journals', compact('ledgers','journals'));
    }

    public function storeJournal(Request $request){
        $journalDetails = $request->validate([
            'date' => 'required|string',
            'ledger_id' => 'required|integer',
            'particular' => 'required|string|max:255',
            'mode' => 'required|string',
        ]);

        Journal::create($journalDetails);

        return redirect()->back()->with('success_msg','Journal created successfully!');
    }

    public function purchases(){
        return view('inc.purchases');
    }
}
