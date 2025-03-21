<?php

namespace App\Http\Controllers\Ledger;

use App\Models\Ledger;
use App\Models\LedgerGroup;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LedgerController extends Controller
{
    //
    public function ledgers(){
        $ledgerGroups = LedgerGroup::orderBy('group_name','asc')->get();
        $ledgers = Ledger::all();
        return view('inc.ledgers', compact('ledgerGroups','ledgers'));
    }

    public function storeLedger(Request $request){
        $ledgerDetails = $request->validate([
            'date' => 'required|date',
            'customer_name' => 'required|string',
            'ledger_type' => 'required|string|max:255',
            'ledger_group' => 'required|integer',
            'mode' => 'required|string|max:255',
            'amount' => 'required|string',
        ]);

        // $existingLedger = Ledger::where('ledger_group', $request->input('ledger_group'))->first();

        // if($existingLedger){
        //     return redirect()->back()->with('error_msg','Ledger already exists!');
        // }

        try{
            Ledger::create($ledgerDetails);
            return redirect()->back()->with('success_msg','Ledger created successfully!');
        }catch(\Throwable $th){
            return $th->getMessage();
        }
    }

    public function ledgerGroup(Request $request){
        $ledgerGroupDetails = $request->validate([
            'group_type' => 'nullable|string',
            'group_name' => 'required|string',
        ]);

        $existingLedgerGroupName = LedgerGroup::where('group_name', $request->input('group_name'))->first();

        if($existingLedgerGroupName){
            return redirect()->back()->with('error_msg','Ledger Group exists!');
        }

        LedgerGroup::create($ledgerGroupDetails);

        return redirect()->back()->with('success_msg'.'Ledger Group Created Successfully!');
    }

    public function ledgerList(Request $request){
        $ledgerGroups = LedgerGroup::all();
        $ledgers = Ledger::orderBy('id','desc')->filter(request(['search']))->get();

        if($request->has(['fromDate','toDate']) && $request->fromDate && $request->toDate){
            $from = $request->fromDate;
            $to = $request->toDate;

            $ledgers = Ledger::whereBetween('date', [$from, $to])->get();
        }
        return view('inc.ledger-list', compact('ledgers','ledgerGroups'));
    }
}
