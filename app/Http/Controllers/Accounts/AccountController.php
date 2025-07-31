<?php

namespace App\Http\Controllers\Accounts;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class AccountController extends Controller
{
    public function accountBalance()
    {
        $companyId = DB::table('companies AS C')
            ->join('administrators AS A', 'C.id', '=', 'A.company_id')
            ->select('C.id AS companyId')
            ->where('A.phone', Auth::user()->username)
            ->orWhere('A.email', Auth::user()->username)
            ->first();

        $bankData = DB::table('banks AS B')
            ->join('city AS C', 'B.region', '=', 'C.id')
            ->select([
                'B.bank_name AS bankName',
                'B.account_name AS accountName',
                'B.account_number AS accountNumber',
                'B.id AS autoId',
            ])
            ->where('B.company_id', $companyId->companyId)
            ->where('B.soft_delete', 0)
            ->orderBy('B.bank_name', 'ASC')
            ->get();

        $accountBalanceData = DB::table('bank_balances AS BA')
            ->join('banks AS B', 'BA.bank_id', '=', 'B.id')
            ->select([
                'B.bank_name AS bank_name',
                'B.account_name AS account_name',
                'B.account_number AS account_number',
                'BA.opening_balance AS opening_balance',
                'BA.current_balance AS current_balance',
                'BA.allow_overdraft AS allow_overdraft',
                'BA.overdraft_limit AS overdraft_limit',
                'BA.as_of_date AS as_of_date',
            ])
            ->where('B.company_id', $companyId->companyId)
            ->get();

        return view('accounts.account-balance', compact([
            'bankData',
            'accountBalanceData'
        ]));
    }

    public function accountBalanceStore(Request $request)
    {
        $request->validate([
            'bank_id' => 'required|integer',
            'opening_balance' => 'required|numeric',
            'allow_overdraft' => 'required|integer',
            'overdraft_limit' => 'nullable|string',
        ]);

        $bankExists = DB::table('banks')->where('id', $request->bank_id)
            ->where('soft_delete', 0)
            ->first();

        if (!$bankExists) {
            return redirect()->back()->with('error_msg', 'Bank does not exists!');
        }

        $bankBalanceData = DB::table('bank_balances')
            ->where('bank_id', $request->bank_id)
            ->whereNot('current_balance', 0)
            ->exists();

        if ($bankBalanceData == true) {
            return redirect()->back()->with('error_msg', 'You can not perform this action for this account number' . ' ' . $bankExists->account_number . ' ' . 'try updating the balance please!');
        }

        $overdraftLimit = 0;

        if ($request->overdraft_limit == null) {
            $overdraftLimit = 0;
        } else {
            $overdraftLimit = $request->overdraft_limit;
        }

        $balanceId = DB::table('bank_balances')->insertGetId([
            'bank_id' => $request->bank_id,
            'opening_balance' => $request->opening_balance,
            'current_balance' => $request->opening_balance,
            'allow_overdraft' => $request->allow_overdraft,
            'overdraft_limit' => $overdraftLimit,
            'as_of_date' => Carbon::now(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $newBalance = number_format(DB::table('bank_balances')->where('id', $balanceId)->first()->current_balance, 2);

        return redirect()->back()->with('success_msg', 'Bank account number ' . ' ' . $bankExists->account_number . ' ' . 'new balance is' . ' ' . $newBalance);
    }

    public function bankStatements(Request $request)
    {
        $companyId = DB::table('companies AS C')
            ->join('administrators AS A', 'C.id', '=', 'A.company_id')
            ->select('C.id AS companyId')
            ->where('A.phone', Auth::user()->username)
            ->orWhere('A.email', Auth::user()->username)
            ->first();

        $pettyCashAccounts = DB::table('banks AS B')
            ->join('bank_transactions AS BT', 'B.id', '=', 'BT.bank_id')
            ->select([
                'B.id AS bankId',
                'B.bank_name AS bankName',
                'B.account_name AS accName',
                'B.account_number AS account_number',
            ])
            ->where('B.company_id', $companyId->companyId)
            ->distinct()
            ->get();

        $statements = collect();
        $fromDate = null;
        $toDate = null;
        $accoutData = null;
        $balanceBroughtForward = 0;
        $totalDr = 0;
        $totalCr = 0;

        if (
            $request->has('bank_id') &&
            $request->has('from_date') &&
            $request->has('to_date') &&
            $request->bank_id != null &&
            $request->from_date != null && $request->to_date
        ) {

            $fromDate = $request->from_date;
            $toDate = $request->to_date;
            $bankId = Crypt::decrypt($request->bank_id);

            $accoutData = DB::table('banks')
                ->where('id', $bankId)
                ->first();

            $balanceBroughtForward = DB::table('bank_transactions')
                ->where('bank_id', $bankId)
                ->where('date', '<', $fromDate)
                ->selectRaw("
        SUM(CASE WHEN type = 'Cr' THEN amount ELSE 0 END) -
        SUM(CASE WHEN type = 'Dr' THEN amount ELSE 0 END) AS current_balance
    ")
                ->value('current_balance');

            $statements = DB::table('bank_transactions AS BT')
                ->join('banks AS B', 'BT.bank_id', '=', 'B.id')
                ->join('expenses AS EXP', 'BT.reference_no', '=', 'EXP.id')
                ->select([
                    'EXP.expense_name AS expName',
                    'EXP.description AS decription',
                    'BT.type AS accType',
                    'BT.amount AS amount',
                    'BT.date AS date',
                ])
                ->where('EXP.status', 1)
                ->where('BT.bank_id', $bankId)
                ->whereBetween('BT.date', [$fromDate, $toDate])
                ->where('EXP.company_id', $companyId->companyId)
                ->where('B.company_id', $companyId->companyId)
                ->get();

            $totalCr = DB::table('bank_transactions')
                ->where('bank_id', $bankId)
                ->whereBetween('date', [$fromDate, $toDate])
                ->where('type', 'Cr')
                ->sum('amount');

            $totalDr = DB::table('bank_transactions')
                ->where('bank_id', $bankId)
                ->whereBetween('date', [$fromDate, $toDate])
                ->where('type', 'Dr')
                ->sum('amount');
        }

        $statementsCounter = $statements->count();

        return view('accounts.bank-statmenets', compact([
            'pettyCashAccounts',
            'statements',
            'fromDate',
            'statementsCounter',
            'toDate',
            'accoutData',
            'balanceBroughtForward',
            'totalCr',
            'totalDr',
        ]));
    }

    public function downloadBankStatement(Request $request)
    {
        $companyId = DB::table('companies AS C')
            ->join('administrators AS A', 'C.id', '=', 'A.company_id')
            ->select('C.id AS companyId')
            ->where('A.phone', Auth::user()->username)
            ->orWhere('A.email', Auth::user()->username)
            ->first();

        try {
            $bankId = Crypt::decrypt($request->bank_id);
        } catch (\Throwable $th) {
            return $th->getMessage();
        }

        $fromDate = $request->fromDate;
        $toDate = $request->toDate;

        $accoutData = DB::table('banks')
            ->where('id', $bankId)
            ->first();

        $balanceBroughtForward = DB::table('bank_transactions')
            ->where('bank_id', $bankId)
            ->where('date', '<', $fromDate)
            ->selectRaw("
        SUM(CASE WHEN type = 'Cr' THEN amount ELSE 0 END) -
        SUM(CASE WHEN type = 'Dr' THEN amount ELSE 0 END) AS current_balance
    ")
            ->value('current_balance');


        $statements = DB::table('bank_transactions AS BT')
            ->join('banks AS B', 'BT.bank_id', '=', 'B.id')
            ->join('expenses AS EXP', 'BT.reference_no', '=', 'EXP.id')
            ->select([
                'EXP.expense_name AS expName',
                'EXP.description AS decription',
                'BT.type AS accType',
                'BT.amount AS amount',
                'BT.date AS date',
            ])
            ->where('EXP.status', 1)
            ->where('BT.bank_id', $bankId)
            ->whereBetween('BT.date', [$fromDate, $toDate])
            ->where('EXP.company_id', $companyId->companyId)
            ->where('B.company_id', $companyId->companyId)
            ->get();

        $totalCr = DB::table('bank_transactions')
            ->where('bank_id', $bankId)
            ->whereBetween('date', [$fromDate, $toDate])
            ->where('type', 'Cr')
            ->sum('amount');

        $totalDr = DB::table('bank_transactions')
            ->where('bank_id', $bankId)
            ->whereBetween('date', [$fromDate, $toDate])
            ->where('type', 'Dr')
            ->sum('amount');

        $pdf = Pdf::loadView('pdf.bank_statement', compact([
            'statements',
            'fromDate',
            'toDate',
            'accoutData',
            'balanceBroughtForward',
            'totalCr',
            'totalDr',
        ]));

        return $pdf->download('bank_statement.pdf');
    }
}
