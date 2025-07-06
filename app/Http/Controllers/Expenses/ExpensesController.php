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
        $subBudgets = DB::table('sub_budgests AS SB')
            ->join('budgets AS B', 'SB.budget_code', '=', 'B.budget_code')
            ->select([
                'SB.id AS subBudgetId',
                'SB.sub_budget_code AS sub_budget_code',
                'SB.sub_budget_description AS description',
                'SB.cost_type AS costType'
            ])
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
            ->join('emplyees AS U', 'EXP.created_by', '=', 'U.id')
            ->select([
                'EXP.expense_name AS exName',
                'EXT.name AS exType',
                'SB.sub_budget_code AS subBudgetCode',
                'SB.sub_budget_description AS description',
                'EXP.amount AS amount',
                'EXP.reference_no AS refNo',
                'EXP.expense_date AS dueDate',
                'U.last_name AS staffName',
            ])
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

        try {
            DB::table('expenses')->insert([
                'expense_name' => $request->expense_name,
                'amount' => $request->amount,
                'expense_type' => $request->expense_type,
                'budget_id' => $request->sub_budget_id,
                'reference_no' => $request->reference_no,
                'description' => $request->description,
                'expense_date' => Carbon::now(),
                'created_by' => $staff,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        } catch (\Throwable $th) {
            return $th->getMessage();
        }

        return redirect()->back()->with('success_msg', 'Expense recorded successfully!');
    }
}
