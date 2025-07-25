<?php

namespace App\Http\Controllers\Budget;

use Carbon\Carbon;
use App\Models\Budget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\ValidationException;

class BudgetController extends Controller
{
    //
    public function budgetCreate()
    {
        $companyId = DB::table('companies AS C')
            ->join('administrators AS A', 'C.id', '=', 'A.company_id')
            ->select('C.id AS companyId')
            ->where('A.phone', Auth::user()->username)
            ->orWhere('A.email', Auth::user()->username)
            ->first();

        $projects = DB::table('projects')
            ->select('*')
            ->where('company_id', $companyId->companyId)
            ->where('soft_delete', 0)
            ->orderBy('id', 'DESC')
            ->get();

        $costTypes = DB::table('cost_types')
            ->select('name', 'id')
            ->where('soft_delete', 0)
            ->orderBy('name', 'ASC')
            ->get();

        $years = collect(range(0, 7))->map(function ($i) {
            return Carbon::now()->subYears($i)->year;
        });

        $budgets = DB::table('budgets AS BG')
            ->join('sub_budgests AS SB', 'BG.budget_name', '=', 'SB.budget_name')
            ->select([
                'BG.id AS autoId',
                'BG.budget_name AS budgetName',
                'BG.budget_year AS budgetYear',
                'BG.budget_code AS budgetCode',
                'BG.currency AS currency',
                'BG.project_name AS projectName',
                'BG.cost_type AS costType',
                'BG.is_approved AS status',
                DB::raw('COUNT(SB.id) AS subCodes'),
                DB::raw('SUM(SB.unit_cost * SB.quantity) AS totalBudgetCost')
            ])
            ->where('BG.company_id', $companyId->companyId)
            ->where('BG.soft_delete', 0)
            ->where('SB.soft_delete', 0)
            ->groupBy([
                'BG.id',
                'BG.budget_name',
                'BG.budget_year',
                'BG.budget_code',
                'BG.currency',
                'BG.project_name',
                'BG.cost_type',
                'BG.is_approved'
            ])
            ->orderBy('BG.id', 'DESC')
            ->get();

        // dd($budgets);

        return view('templates.budgets', compact('projects', 'years', 'costTypes', 'budgets'));
    }

    public function storeProject(Request $request)
    {
        $request->validate([
            'project_name' => 'required|string|max:30',
        ]);

        $companyId = DB::table('companies AS C')
            ->join('administrators AS A', 'C.id', '=', 'A.company_id')
            ->select('C.id AS companyId')
            ->where('A.phone', Auth::user()->username)
            ->orWhere('A.email', Auth::user()->username)
            ->first();

        $projectExsists = DB::table('projects')
            ->where('company_id', $companyId->companyId)
            ->where('name', $request->project_name)
            ->where('soft_delete', 0)
            ->exists();

        if ($projectExsists == true) {
            return redirect()->back()->with('error_msg', 'Project already exists!');
        }

        try {
            DB::table('projects')->insert([
                'name' => $request->project_name,
                'company_id' => $companyId->companyId,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        } catch (\Throwable $th) {
            return $th->getMessage();
        }

        return redirect()->back()->with('success_msg', 'New project added successfully!');
    }

    public function budgetStore(Request $request)
    {
        $request->validate([
            // BUDGET
            'budget_year' => 'required|integer',
            'currency' => 'nullable|string',
            'cost_type' => 'nullable|string',
            'budget_name' => 'nullable|string',
            'budget_code' => 'nullable|string',
            'project_name' => 'nullable|string',

            // SUB-BUDGET
            'sub_budget_code' => 'nullable|array',
            'sub_budget_code.*' => 'nullable|string',

            'sub_budget_description' => 'nullable|array',
            'sub_budget_description.*' => 'nullable|string',

            'unit_cost' => 'nullable|array',
            'unit_cost.*' => 'nullable|numeric',

            'quantity' => 'nullable|array',
            'quantity.*' => 'nullable|integer',

            'unit_meausre' => 'nullable|array',
            'unit_meausre.*' => 'nullable|string',
        ]);

        $companyId = DB::table('companies AS C')
            ->join('administrators AS A', 'C.id', '=', 'A.company_id')
            ->select('C.id AS companyId')
            ->where('A.phone', Auth::user()->username)
            ->orWhere('A.email', Auth::user()->username)
            ->first();

        try {
            $budgetData = Budget::create([
                'budget_year' => $request->budget_year,
                'currency' => $request->currency,
                'cost_type' => $request->cost_type,
                'budget_name' => $request->budget_name,
                'budget_code' => $request->budget_code,
                'project_name' => $request->project_name,
                'created_by' => Auth::user()->id,
                'company_id' => $companyId->companyId,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            foreach ($request->sub_budget_code as $key => $code) {
                DB::table('sub_budgests')->insert([
                    'budget_name' => $budgetData->budget_name,
                    'budget_code' => $budgetData->budget_code,
                    'cost_type' => $budgetData->cost_type,
                    'sub_budget_code' => $request->sub_budget_code[$key],
                    'sub_budget_description' => $request->sub_budget_description[$key],
                    'unit_cost' => $request->unit_cost[$key],
                    'quantity' => $request->quantity[$key],
                    'unit_meausre' => $request->unit_meausre[$key],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        } catch (\Throwable $th) {
            return $th->getMessage();
        }

        return redirect()->back()->with('success_msg', 'New budget for the year' . ' ' . $budgetData->budget_year . ' ' . 'created successfully!');
    }

    public function viewBudget($encryptedId)
    {
        try {
            $budgetId = Crypt::decrypt($encryptedId);
        } catch (\Throwable $th) {
            return $th->getMessage();
        }

        $companyId = DB::table('companies AS C')
            ->join('administrators AS A', 'C.id', '=', 'A.company_id')
            ->select('C.id AS companyId')
            ->where('A.phone', Auth::user()->username)
            ->orWhere('A.email', Auth::user()->username)
            ->first();

        $budget = Budget::select('*')
            ->where('company_id', $companyId->companyId)
            ->where('id', $budgetId)
            ->first();

        $subBudgets = DB::table('sub_budgests')
            ->select('*')
            ->where('budget_code', $budget->budget_code)
            ->where('soft_delete', 0)
            ->get();

        // dd($subBudgets);

        return view('templates.view-budget', compact('subBudgets', 'budget', 'budgetId'));
    }

    public function budgetReview(Request $request)
    {
        $subudgets = collect();
        $budget = null;

        $companyId = DB::table('companies AS C')
            ->join('administrators AS A', 'C.id', '=', 'A.company_id')
            ->select('C.id AS companyId')
            ->where('A.phone', Auth::user()->username)
            ->orWhere('A.email', Auth::user()->username)
            ->first();

        $budgetYears = DB::table('budgets')
            ->select('budget_year')
            ->where('company_id', $companyId->companyId)
            ->where('soft_delete', 0)
            ->orderBy('id', 'DESC')
            ->pluck('budget_year');

        $budgetCodes = DB::table('budgets')
            ->select('budget_code', 'budget_year')
            ->where('company_id', $companyId->companyId)
            ->where('soft_delete', 0)
            ->orderBy('id', 'DESC')
            ->get();

        $projects = DB::table('budgets')
            ->select('project_name')
            ->where('company_id', $companyId->companyId)
            ->where('soft_delete', 0)
            ->orderBy('id', 'DESC')
            ->distinct()
            ->pluck('project_name');

        if (
            $request->has('searchProject') &&
            $request->has('searchCode') &&
            $request->has('searchYear') &&
            $request->searchProject != null &&
            $request->searchCode != null &&
            $request->searchYear != null
        ) {
            $budget = DB::table('budgets')->select([
                'budget_name',
                'project_name',
                'currency',
                'cost_type',
                'budget_code',
                'budget_year',
                'created_at',
                'is_approved'
            ])
                ->where('company_id', $companyId->companyId)
                ->where('project_name', $request->searchProject)
                ->where('budget_year', $request->searchYear)
                ->where('budget_code', $request->searchCode)
                ->where('soft_delete', 0)
                ->first();

            if ($budget != null) {
                $subudgets = DB::table('sub_budgests')
                    ->select('*')
                    ->where('budget_name', $budget->budget_name)
                    ->where('soft_delete', 0)
                    ->get();
            }
        }
        return view('templates.budget-review', compact([
            'budget',
            'subudgets',
            'budgetYears',
            'projects',
            'budgetCodes'
        ]));
    }

    public function budgetApproval(Request $request)
    {
        try {
            $validated = $request->validate([
                'sub_budget_code' => 'required|array',
                'sub_budget_code.*' => 'required|string',

                'unit_cost' => 'required|array',
                'unit_cost.*' => 'required|string',

                'quantity' => 'required|array',
                'quantity.*' => 'required|string',

                'project_name' => 'required|string',
                'budget_year' => 'required|string',
                'confirm' => 'required|string',
            ]);
        } catch (ValidationException $e) {
            dd('Validation failed', $e->errors(), $request->all());
        }

        $companyId = DB::table('companies AS C')
            ->join('administrators AS A', 'C.id', '=', 'A.company_id')
            ->select('C.id AS companyId')
            ->where('A.phone', Auth::user()->username)
            ->orWhere('A.email', Auth::user()->username)
            ->first();

        if ($request->has('confirm') && $request->confirm != null && Crypt::decrypt($request->confirm) == 1) {

            try {
                $decryptedProject = Crypt::decrypt($request->project_name);
                $decryptedBudgetYear = Crypt::decrypt($request->budget_year);
            } catch (\Throwable $th) {
                return $th->getMessage();
            }

            $budgetExists = DB::table('budgets')
                ->where('company_id', $companyId->companyId)
                ->where('project_name', $decryptedProject)
                ->where('budget_year', $decryptedBudgetYear)
                ->exists();

            if ($budgetExists == true) {
                $thisBudget = DB::table('budgets')
                    ->where('company_id', $companyId->companyId)
                    ->where('project_name', $decryptedProject)
                    ->where('budget_year', $decryptedBudgetYear)
                    ->first();

                DB::table('budgets')
                    ->where('company_id', $companyId->companyId)
                    ->where('budget_year', $decryptedBudgetYear)
                    ->where('project_name', $decryptedProject)
                    ->update([
                        'is_approved' => true,
                        'approved_by' => Auth::user()->id,
                    ]);

                foreach ($request->unit_cost as $key => $unitCost) {
                    $cleanCost = floatval(str_replace(',', '', $unitCost));
                    $cleanQty = floatval(str_replace(',', '', $request->quantity[$key]));
                    $code = $request->sub_budget_code[$key];

                    DB::table('sub_budgests')
                        ->where('budget_code', $thisBudget->budget_code)
                        ->where('budget_name', $thisBudget->budget_name)
                        ->where('sub_budget_code', $code)
                        ->update([
                            'unit_cost' => $cleanCost,
                            'quantity' => $cleanQty,
                        ]);
                }

                return redirect()->back()->with('success_msg', 'Budget approbed successfully!');
            }
        }
    }

    public function appendSubBudgets(Request $request)
    {
        $request->validate([
            'sub_budget_code' => 'nullable|array',
            'sub_budget_code.*' => 'nullable|string',

            'sub_budget_description' => 'nullable|array',
            'sub_budget_description.*' => 'nullable|string',

            'unit_cost' => 'required|array',
            'unit_cost.*' => 'required|numeric',

            'quantity' => 'nullable|array',
            'quantity.*' => 'nullable|integer',

            'unit_meausre' => 'nullable|array',
            'unit_meausre.*' => 'nullable|string',

            'budget_code' => 'required|string',
            'project_name' => 'required|string',
        ]);

        $companyId = DB::table('companies AS C')
            ->join('administrators AS A', 'C.id', '=', 'A.company_id')
            ->select('C.id AS companyId')
            ->where('A.phone', Auth::user()->username)
            ->orWhere('A.email', Auth::user()->username)
            ->first();

        $existingBudget = DB::table('budgets')
            ->where('company_id', $companyId->companyId)
            ->where('budget_code', $request->budget_code)
            ->where('soft_delete', 0)
            ->first();

        // dd($existingBudget);

        foreach ($request->sub_budget_code as $key => $code) {

            $existingSubBudget = DB::table('sub_budgests')
                ->where('sub_budget_code', $request->sub_budget_code[$key])
                ->where('soft_delete', 0)
                ->exists();

            if ($existingSubBudget == true) {
                return redirect()->back()->with('error_msg', 'Sub budget exists!');
            }

            DB::table('sub_budgests')->insert([
                'budget_name' => $existingBudget->budget_name,
                'budget_code' => $existingBudget->budget_code,
                'sub_budget_code' => $request->sub_budget_code[$key],
                'sub_budget_description' => $request->sub_budget_description[$key],
                'unit_cost' => $request->unit_cost[$key],
                'quantity' => $request->quantity[$key],
                'unit_meausre' => $request->unit_meausre[$key],
                'cost_type' => $existingBudget->cost_type,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        return redirect()->back()->with('success_msg', 'New sub-budgets added successfully to a budget' . ' ' . $existingBudget->budget_name);

        // dd($request->all());
    }

    public function removeSubBudget(Request $request)
    {
        $request->validate([
            'sub_budget_id' => 'required|string',
        ]);

        try {
            $decryptedId = Crypt::decrypt($request->sub_budget_id);
        } catch (\Throwable $th) {
            return $th->getMessage();
        }

        $subBudgetExists = DB::table('sub_budgests')
            ->where('id', $decryptedId)
            ->where('soft_delete', 0)
            ->exists();
        if ($subBudgetExists == true) {
            DB::table('sub_budgests')
                ->where('id', $decryptedId)
                ->where('soft_delete', 0)
                ->update([
                    'soft_delete' => 1,
                ]);
        }

        return redirect()->back()->with('success_msg', 'Sub budget code removed successfully!');
    }

    public function budgetRollOut(Request $request)
    {
        $subudgets = collect();
        $budget = null;
        $newYear = null;

        $companyId = DB::table('companies AS C')
            ->join('administrators AS A', 'C.id', '=', 'A.company_id')
            ->select('C.id AS companyId')
            ->where('A.phone', Auth::user()->username)
            ->orWhere('A.email', Auth::user()->username)
            ->first();

        $budgetYears = DB::table('budgets')
            ->select('budget_year')
            ->where('company_id', $companyId->companyId)
            ->where('soft_delete', 0)
            ->orderBy('id', 'DESC')
            ->pluck('budget_year');

        $budgetCodes = DB::table('budgets')
            ->select('budget_year', 'budget_code')
            ->where('company_id', $companyId->companyId)
            ->where('soft_delete', 0)
            ->orderBy('id', 'DESC')
            ->get();

        $projects = DB::table('budgets')
            ->select('project_name')
            ->where('company_id', $companyId->companyId)
            ->where('soft_delete', 0)
            ->orderBy('id', 'DESC')
            ->distinct()
            ->pluck('project_name');

        if (
            $request->has('searchProject') &&
            $request->has('searchCode') &&
            $request->has('searchYear') &&
            $request->searchProject != null &&
            $request->searchCode != null &&
            $request->searchYear != null
        ) {
            $budget = DB::table('budgets')->select([
                'budget_name',
                'project_name',
                'currency',
                'cost_type',
                'budget_code',
                'budget_year',
                'created_at',
                'is_approved'
            ])
                ->where('company_id', $companyId->companyId)
                ->where('project_name', $request->searchProject)
                ->where('budget_year', $request->searchYear)
                ->where('budget_code', $request->searchCode)
                // ->where('is_approved', 1)
                ->where('soft_delete', 0)
                ->first();

            $newYear = $request->newYear;

            // dd($budget);

            if ($budget != null) {
                $subudgets = DB::table('sub_budgests')
                    ->select('*')
                    ->where('budget_name', $budget->budget_name)
                    ->where('soft_delete', 0)
                    ->get();
            }
        }
        return view('templates.budget-roll-out', compact([
            'budget',
            'subudgets',
            'budgetYears',
            'projects',
            'newYear',
            'budgetCodes'
        ]));
    }

    public function budgetRollOutRecreate(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'sub_budget_code' => 'required|array',
                'sub_budget_code.*' => 'required|string',

                'sub_budget_description' => 'nullable|array',
                'sub_budget_description.*' => 'nullable|string',

                'unit_cost' => 'required|array',
                'unit_cost.*' => 'required|string',

                'quantity' => 'nullable|array',
                'quantity.*' => 'nullable|integer',

                'unit_meausre' => 'nullable|array',
                'unit_meausre.*' => 'nullable|string',

                'budget_name' => 'required|string',
                'budget_year' => 'required|string',
                'project_name' => 'required|string',
                'new_budget_year' => 'required|integer',
                'currency' => 'required|string',
                'budget_code' => 'required|string|max:20',
                'old_budget_code' => 'required|string',
            ]);
        } catch (ValidationException $e) {
            dd('Data seems to be invalid!', $e->errors(), $request->all());
        }

        // dd($request->all());

        $companyId = DB::table('companies AS C')
            ->join('administrators AS A', 'C.id', '=', 'A.company_id')
            ->select('C.id AS companyId')
            ->where('A.phone', Auth::user()->username)
            ->orWhere('A.email', Auth::user()->username)
            ->first();

        $newBudgetYear = $request->new_budget_year;

        $existingBudget = DB::table('budgets')
            ->where('company_id', $companyId->companyId)
            ->where('budget_year', $newBudgetYear)
            ->where('budget_code', $request->budget_code)
            ->exists();

        // dd($existingBudget);

        $oldBudget = DB::table('budgets')
            ->where('company_id', $companyId->companyId)
            ->where('budget_year', $request->budget_year)
            ->where('budget_name', $request->budget_name)
            ->where('budget_code', $request->old_budget_code)
            ->first();

        // dd($oldBudget);

        if ($existingBudget == true) {
            return redirect()->back()->with('error_msg', 'This budget code for the year' . ' ' . $newBudgetYear . ' ' . 'exists!');
        }

        // dd($request->all());

        $newBudgetData =  Budget::create([
            'budget_year' => $newBudgetYear,
            'currency' => $request->currency ?? 'TZS',
            'cost_type' => $oldBudget->cost_type,
            'budget_name' => $request->budget_name ?? $oldBudget->budget_name,
            'budget_code' => $request->budget_code,
            'project_name' => $request->project_name ?? $oldBudget->project_name,
            'created_by' => Auth::user()->id,
            'company_id' => $companyId->companyId,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        foreach ($request->sub_budget_code as $key => $code) {
            $unitCost = $request->unit_cost[$key] ?? null;
            $quantity = $request->quantity[$key] ?? 0;
            $description = $request->sub_budget_description[$key] ?? null;
            $unitMeasure = $request->unit_meausre[$key] ?? null;

            $cleanCost = floatval(str_replace(',', '', $unitCost));
            $cleanQuantity = floatval(str_replace(',', '', $quantity));

            DB::table('sub_budgests')->insert([
                'budget_name' => $newBudgetData->budget_name,
                'budget_code' => $newBudgetData->budget_code,
                'sub_budget_code' => $code,
                'sub_budget_description' => $description,
                'unit_cost' => $cleanCost,
                'quantity' => $cleanQuantity,
                'unit_meausre' => $unitMeasure,
                'cost_type' => $newBudgetData->cost_type,
                'created_at' => Carbon::now(),
            ]);
        }

        return redirect()->back()->with('success_msg', 'Budget created successfully!');
    }

    public function budgetReports(Request $request)
    {
        $companyId = DB::table('companies AS C')
            ->join('administrators AS A', 'C.id', '=', 'A.company_id')
            ->select('C.id AS companyId')
            ->where('A.phone', Auth::user()->username)
            ->orWhere('A.email', Auth::user()->username)
            ->first();

        $projects = DB::table('projects')
            ->select([
                'id',
                'name',
            ])
            ->where('company_id', $companyId->companyId)
            ->where('soft_delete', 0)
            ->orderBy('id', 'DESC')
            ->distinct()
            ->get();

        $budgetYrs = DB::table('budgets')
            ->select('budget_year AS year')
            ->where('company_id', $companyId->companyId)
            ->where('soft_delete', 0)
            ->orderBy('id', 'DESC')
            ->distinct()
            ->get();

        $branch = DB::table('budgets')
            ->select('branch_name AS branch')
            ->where('company_id', $companyId->companyId)
            ->where('soft_delete', 0)
            ->orderBy('branch_name', 'ASC')
            ->distinct()
            ->get();

        $reports = collect();

        if ($request->has('from_date') && $request->has('to_date') && $request->has('year') && $request->has('year') && $request->has('branch')) {
            $reports = DB::table('sub_budgests AS SB')
                ->join('expenses AS EX', 'SB.id', '=', 'EX.budget_id')
                ->join('budgets AS B', 'SB.budget_code', '=', 'B.budget_code')
                ->select([
                    'SB.cost_type AS costType',
                    'SB.sub_budget_code AS sub_budget_code',
                    'SB.unit_cost AS amount',
                    DB::raw('SUM(EX.amount) AS totalAmount'),
                ])
                ->whereBetween('EX.expense_date', [$request->from_date, $request->to_date])
                ->where('B.company_id', $companyId->companyId)
                ->where('EX.company_id', $companyId->companyId)
                ->where('B.budget_year', $request->year)
                ->where('B.branch_name', $request->branch)
                ->where('B.soft_delete', 0)
                ->where('EX.soft_delete', 0)
                ->where('SB.soft_delete', 0)
                ->groupBy('SB.cost_type', 'SB.sub_budget_code', 'SB.unit_cost')
                ->get();
            // dd($reports);
        }

        return view('templates.budget-reports', compact([
            'projects',
            'budgetYrs',
            'branch',
            'reports'
        ]));
    }

    public function staffBudgetCodes(Request $request)
    {
        $companyId = DB::table('companies AS C')
            ->join('administrators AS A', 'C.id', '=', 'A.company_id')
            ->select('C.id AS companyId')
            ->where('A.phone', Auth::user()->username)
            ->orWhere('A.email', Auth::user()->username)
            ->first();

        $years = DB::table('budgets')
            ->select('budget_year AS yr')
            ->where('company_id', $companyId->companyId)
            ->where('soft_delete', 0)
            ->orderBy('id', 'DESC')
            ->get();

        $projects = DB::table('budgets')
            ->select('project_name AS proj')
            ->where('company_id', $companyId->companyId)
            ->where('soft_delete', 0)
            ->distinct()
            ->get();

        $projectName = null;
        $budgetYear = null;
        $emplyees = collect();

        if ($request->ajax()) {
            $projectName = $request->projectSearch;
            $budgetYear = $request->budgetYrSearch;
            $emplyees = DB::table('emplyees')
                ->select([
                    'id',
                    'salary_amount',
                    'first_name',
                    'last_name'
                ])
                ->where('company_id', $companyId->companyId)
                ->where('soft_delete', 0)->orderBy('first_name', 'ASC')->get();

            $budget = DB::table('budgets AS B')
                ->join('sub_budgests AS SB', 'B.budget_code', '=', 'SB.budget_code')
                ->select('B.id', 'B.budget_code', 'B.budget_name', 'SB.sub_budget_code')
                ->where('company_id', $companyId->companyId)
                ->where('B.budget_year', $budgetYear)
                ->where('B.project_name', $projectName)
                ->where('B.soft_delete', 0)
                ->where('SB.soft_delete', 0)
                ->get();

            return response()->json([
                'html' => view('partials.staff-budget-data', [
                    'projectName' => $projectName,
                    'budgetYear' => $budgetYear,
                    'emplyees' => $emplyees,
                    'budgets' => $budget,
                ])->render()
            ]);
        }

        $stafBudgetCodes = DB::table('staff_budget_codes AS SBC')
            ->join('emplyees AS EM', 'SBC.staff_id', '=', 'EM.id')
            ->join('budgets AS B', 'SBC.budget_code', '=', 'B.id')
            ->select('SBC.*', 'B.budget_code AS budget_code_name', 'EM.first_name', 'EM.middle_name', 'EM.last_name')
            ->where('B.company_id', $companyId->companyId)
            ->where('SBC.soft_delete', 0)
            ->orderBy('SBC.id', 'DESC')
            ->get();

        // dd($projectName);

        return view('templates.staff-budget-codes', compact(
            'years',
            'projects',
            'projectName',
            'budgetYear',
            'stafBudgetCodes'
        ));
    }

    public function staffSubBudgetCodes(Request $request)
    {
        $request->validate([
            'project_name' => 'required|string',
            'budget_year' => 'required|integer',

            'staff_id' => 'required|array',
            'staff_id.*' => 'required|integer',

            'budget_cost' => 'required|array',
            'budget_cost.*' => 'required|decimal:0,2',

            'budget_code' => 'required|array',
            'budget_code.*' => 'required|integer',

            'sub_budget_code' => 'required|array',
            'sub_budget_code.*' => 'required|string',
        ]);

        foreach ($request->sub_budget_code as $key => $code) {
            $budget = DB::table('sub_budgests')->where('sub_budget_code', $request->sub_budget_code[$key])->first();
            $budgetName = $budget->budget_name;

            DB::table('staff_budget_codes')->insert([
                'project_name' => $request->project_name,
                'budget_year' => $request->budget_year,
                'staff_id' => $request->staff_id[$key],
                'budget_cost' => $request->budget_cost[$key],
                'budget_code' => $request->budget_code[$key],
                'sub_budget_code' => $request->sub_budget_code[$key],
                'budget_name' => $budgetName,
                'created_by' => Auth::user()->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        return redirect()->back()->with('success_msg', 'Sub-budget code assigned successfully!');
    }
}
