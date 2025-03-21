<?php

namespace App\Http\Controllers\Bank;

use App\Models\Bank;
use App\Models\Transfer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;

class BankController extends Controller
{
    //
    public function bank(){
        $banks = Bank::orderBy('bank_name','asc')->filter(request(['search']))->get();
        return view('inc.banks', compact('banks'));
    }

    public function storeBank(Request $request){
        $bankDetails = $request->validate([
            'bank_name' => 'required|string|max:255',
            'branch' => 'required|string|max:255',
            'account_name' => 'required|string|max:255',
            'account_no' => ['required', Rule::unique('banks', 'account_no')],
            'acc_holder' => 'required|string|max:255',
            'phone' => 'required|string|max:13|min:10',
            'balance' => 'required|string',
        ]);

        $existingAccountNo = Bank::where('account_no', $request->input('account_no'))->first();

        if($existingAccountNo){
            return redirect()->back()->with('error_msg','Bank Number is already registered!');
        }

        try{
            Bank::create($bankDetails);

            return redirect()->back()->with('success_msg','Bank addedd successfully!');

        }catch(\Throwable $th){

            return $th->getMessage();
            
        }
    }

    public function transfers(){
        $transfers = Transfer::orderBy('id','desc')->filter(request(['search']))->get();
        $banks = Bank::orderBy('account_name','asc')->get();
        return view('inc.transfers', compact('banks','transfers'));
    }

    public function createTransfer(Request $request){
        $transferDetails = $request->validate([
            'staff_id' => 'required|integer',
            'from_account' => 'required|string',
            'to_account' => 'required|string',
            'amount' => 'required|string',
            'note' => 'nullable|string',
        ]);

        if ($request->input('from_account') === $request->input('to_account')) {
            return redirect()->back()->with('error_msg', 'Cannot transfer to the same account: ' . $transferDetails['to_account']);
        }

        try{
            Transfer::create($transferDetails);

            return redirect()->back()->with('success_msg', 'Transfer from: ' . $transferDetails['from_account'] . 'to: ' . $transferDetails['to_account'] . 'was successful');
        }catch(\Throwable $th){
            return $th->getMessage();
        }
    }
}
