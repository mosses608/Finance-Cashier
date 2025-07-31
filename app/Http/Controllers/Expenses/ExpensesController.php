<?php

namespace App\Http\Controllers\Expenses;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ExpensesController extends Controller
{
    //
    public function expenses()
    {
        $companyId = Auth::user()->company_id;

        $subBudgets = DB::table('sub_budgests AS SB')
            ->join('budgets AS B', 'SB.budget_code', '=', 'B.budget_code')
            ->select([
                'SB.id AS subBudgetId',
                'SB.sub_budget_code AS sub_budget_code',
                'SB.sub_budget_description AS description',
                'SB.cost_type AS costType'
            ])
            ->where('B.company_id', $companyId)
            ->where('B.is_approved', 1)
            ->where('SB.soft_delete', 0)
            ->where('B.soft_delete', 0)
            ->where('B.budget_year', Carbon::now()->year)
            ->get();

        // dd($subBudgets);

        $expensesTypes = DB::table('expenses_type')
            ->select([
                'name',
                'id'
            ])
            ->where('soft_delete', 0)
            ->orderBy('name', 'ASC')
            ->get();

        $expenses = DB::table('expenses AS EXP')
            ->join('sub_budgests AS SB', 'EXP.budget_id', '=', 'SB.id')
            ->join('expenses_type AS EXT', 'EXP.expense_type', '=', 'EXT.id')
            ->join('administrators AS U', 'EXP.created_by', '=', 'U.id')
            ->select([
                'EXP.expense_name AS exName',
                'EXT.name AS exType',
                'SB.sub_budget_code AS subBudgetCode',
                'SB.sub_budget_description AS description',
                'EXP.amount AS amount',
                'EXP.reference_no AS refNo',
                'EXP.expense_date AS dueDate',
                'U.names AS staffName',
            ])
            ->whereNull('EXP.status')
            ->where('EXP.company_id', $companyId)
            ->where('EXP.soft_delete', 0)
            ->where('SB.soft_delete', 0)
            ->orderByDesc('EXP.id')
            ->get();

        // dd($expenses);

        return view('templates.expenses', compact('subBudgets', 'expensesTypes', 'expenses'));
    }

    public function storeExpenses(Request $request)
    {
        $request->validate([
            'expense_name' => 'required|string|max:50',
            'amount' => 'required|numeric',
            'expense_type' => 'required|integer',
            'sub_budget_id' => 'required|integer',
            'reference_no' => 'nullable|string',
            'description' => 'nullable|string|max:255',
            'expense_date' => 'required|string',
        ]);

        $staff = Auth::user()->id;
        // dd($staff);

        $companyId = Auth::user()->company_id;

        try {
            DB::table('expenses')->insert([
                'expense_name' => $request->expense_name,
                'amount' => $request->amount,
                'expense_type' => $request->expense_type,
                'budget_id' => $request->sub_budget_id,
                'reference_no' => $request->reference_no,
                'description' => $request->description,
                'expense_date' => Carbon::now(),
                'company_id' => $companyId,
                'created_by' => $staff,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        } catch (\Throwable $th) {
            return $th->getMessage();
        }

        return redirect()->back()->with('success_msg', 'Expense recorded successfully!');
    }

    public function paymentRequests()
    {
        $companyId = Auth::user()->company_id;

        $expenses = DB::table('expenses AS EXP')
            ->join('sub_budgests AS SB', 'EXP.budget_id', '=', 'SB.id')
            ->join('expenses_type AS EXT', 'EXP.expense_type', '=', 'EXT.id')
            ->join('administrators AS U', 'EXP.created_by', '=', 'U.id')
            ->select([
                'EXP.id AS expenseId',
                'EXP.expense_name AS exName',
                'EXT.name AS exType',
                'SB.sub_budget_code AS subBudgetCode',
                'SB.sub_budget_description AS description',
                'EXP.amount AS amount',
                'EXP.reference_no AS refNo',
                'EXP.expense_date AS dueDate',
                'U.names AS staffName',
            ])
            ->where('EXP.status')
            ->where('EXP.company_id', $companyId)
            ->where('EXP.soft_delete', 0)
            ->where('SB.soft_delete', 0)
            ->orderByDesc('EXP.id')
            ->get();

        $accountBalanceData = DB::table('bank_balances AS BA')
            ->join('banks AS B', 'BA.bank_id', '=', 'B.id')
            ->select([
                'B.id AS bankId',
                'B.bank_name AS bank_name',
                'B.account_name AS account_name',
                'B.account_number AS account_number',
                'BA.opening_balance AS opening_balance',
                'BA.current_balance AS current_balance',
                'BA.allow_overdraft AS allow_overdraft',
            ])
            ->where('B.company_id', $companyId)
            ->get();

        // dd($accountBalanceData);

        return view('templates.payment-requests', compact([
            'expenses',
            'accountBalanceData'
        ]));
    }

    public function approvePayments(Request $request)
    {
        $request->validate([
            'check_payment' => 'required|array',
            'bank_id' => 'required|integer',
            'approve' => 'required|string',
        ]);

        $checkedPayments = $request->input('check_payment');
        $crInputs = $request->input('cr', []);
        $drInputs = $request->input('dr', []);

        $bankData = DB::table('banks')->where('id', $request->bank_id)->first();
        $bankBalanceData = DB::table('bank_balances')->where('bank_id', $request->bank_id)->first();

        if (!$bankBalanceData) {
            return back()->with('error_msg', 'Bank information does not exist in our database!');
        }

        $currentBalance = $bankBalanceData->current_balance;
        $selectedPaymentCost = 0;
        $failed = [];

        foreach ($checkedPayments as $paymentId) {
            $payment = DB::table('expenses')->where('id', $paymentId)->first();

            if (!$payment) {
                $failed[] = "Payment request (ID: $paymentId) not found!";
                continue;
            }

            $type = null;
            if (isset($crInputs[$paymentId])) {
                $type = 'Cr';
            } elseif (isset($drInputs[$paymentId])) {
                $type = 'Dr';
            } else {
                $failed[] = "CR/DR not selected for payment ID: $paymentId";
                continue;
            }

            DB::table('bank_transactions')->insert([
                'bank_id' => $request->bank_id,
                'date' => Carbon::now(),
                'amount' => $payment->amount,
                'type' => $type,
                'reference_no' =>$paymentId,
                'description' => 'Payment approved',
                'related_module' => 'Expenses',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            if ($type == 'Dr') {
                $selectedPaymentCost += $payment->amount;
            } elseif ($type == 'Cr') {
                $selectedPaymentCost -= $payment->amount;
            }

            DB::table('expenses')->where('id', $paymentId)->update([
                'status' => 1,
            ]);
        }

        $newBalance = $currentBalance - $selectedPaymentCost;

        if ($bankBalanceData->allow_overdraft == 0 && $newBalance < 0) {
            return redirect()->back()->with('error_msg', 'Transaction failed: insufficient funds in ' . $bankData->bank_name);
        }

        if ($bankBalanceData->allow_overdraft != 0 && $newBalance < -$bankBalanceData->overdraft_limit) {
            return redirect()->back()->with('error_msg', 'Transaction exceeds allowed overdraft limit.');
        }

        DB::table('bank_balances')->where('bank_id', $request->bank_id)
            ->update([
                'current_balance' => $newBalance,
                'as_of_date' => Carbon::now(),
            ]);

        if (!empty($failed)) {
            return redirect()->back()->with('warning_msg', 'Some payments failed: ' . implode(', ', $failed));
        }

        return redirect()->back()->with('success_msg', 'Selected payments approved successfully!');
    }
}
