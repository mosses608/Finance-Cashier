<?php

namespace App\Http\Controllers\Pages;

use Carbon\Carbon;
use App\Models\Bank;
use App\Models\Employees;
use App\Models\Stakeholder;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PageController extends Controller
{
    //
    public function welcome()
    {
        return view('welcome');
    }

    public function dashboard()
    {
        $todayDate = Carbon::now()->format('Y-m-d');

        $totalAmount = 0;
        $todayTransactions = Transaction::whereDate('created_at', $todayDate)->get();

        foreach ($todayTransactions as $td) {
            $totalAmount += $td->selling_price;
        }

        $lastFiveTransactions = Transaction::orderBy('id', 'desc')->paginate(4);
        $transactions = Transaction::orderBy('created_at', 'asc')->get();
        return view(
            'inc.dashboard',
            compact(
                'transactions',
                'lastFiveTransactions',
                'todayDate',
                'todayTransactions',
                'totalAmount',
            )
        );
    }

    function shortNumberFormat($number, $precision = 1)
    {
        if ($number >= 1000000000) {
            return round($number / 1000000000, $precision) . 'B';
        } elseif ($number >= 1000000) {
            return round($number / 1000000, $precision) . 'M';
        } elseif ($number >= 1000) {
            return round($number / 1000, $precision) . 'K';
        }

        return $number;
    }

    public function testPage()
    {
        $totalCustomers = DB::table('stakeholders')
            ->where('soft_delete', 0)
            ->count();

        $expensesCounter = DB::table('expenses AS EXP')
            ->join('budgets AS B', 'EXP.budget_id', '=', 'B.id')
            ->where('B.budget_year', Carbon::now()->year)
            ->where('B.soft_delete', 0)
            ->where('EXP.soft_delete', 0)
            ->count();

        $expsnesAmount = DB::table('expenses AS EXP')
            ->join('budgets AS B', 'EXP.budget_id', '=', 'B.id')
            ->where('B.budget_year', Carbon::now()->year)
            ->where('B.soft_delete', 0)
            ->where('EXP.soft_delete', 0)
            ->sum('amount');

        $salesAmount = DB::table('sales')
            ->whereYear('created_at', Carbon::now()->year)
            ->where('soft_delete', 0)
            ->sum('amount_paid');

        $shortSales = DB::table('sales')
            ->whereDate('created_at', Carbon::today())
            ->where('soft_delete', 0)
            ->sum('amount_paid');

        $newIncome = $this->shortNumberFormat($salesAmount - $expsnesAmount);

        $todaySales = $this->shortNumberFormat($shortSales);

        $customers = DB::table('stakeholders')
            ->where('stakeholder_category', 1)
            ->select('name', 'address', 'phone', 'email')
            ->orderBy('name', 'ASC')
            ->limit(10)
            ->get();

        $salesTransactions = DB::table('sales AS ST')
            ->join('invoice AS I', 'ST.invoice_id', '=', 'I.id')
            ->select([
                'I.customer_id AS customerId',
                'ST.created_at AS createdDate',
                'ST.amount_paid AS amount',
                'ST.is_paid AS isPaid',
                'ST.status AS status',
            ])
            ->where('I.soft_delete', 0)
            ->where('ST.soft_delete', 0)
            ->orderBy('ST.id', 'DESC')
            ->get();

        $onlineUsers = DB::table('auth')
            ->where('is_online', 1)
            ->count();

        $authUsers = $onlineUsers = DB::table('auth')->count();

        $startOfWeek = Carbon::now()->startOfWeek()->format('M d, Y');
        $endOfWeek = Carbon::now()->endOfWeek()->format('M d, Y');

        $start = Carbon::now()->startOfWeek();

        $end = Carbon::now()->endOfWeek();

        $weeklySales = DB::table('sales')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(amount_paid) as total'))
            ->whereBetween('created_at', [$start, $end])
            ->where('soft_delete', 0)
            ->groupBy('date')
            ->pluck('total', 'date');

        $weeklyExpenses = DB::table('expenses')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(amount) as total'))
            ->whereBetween('created_at', [$start, $end])
            ->where('soft_delete', 0)
            ->groupBy('date')
            ->pluck('total', 'date');

        $labels = [];
        $salesData = [];
        $expensesData = [];

        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
            $dayName = $date->format('D');
            $labels[] = $dayName;
            $key = $date->toDateString();

            $salesData[] = $weeklySales[$key] ?? 0;
            $expensesData[] = $weeklyExpenses[$key] ?? 0;
        }

        return view('inc.home', compact([
            'totalCustomers',
            'expensesCounter',
            'todaySales',
            'newIncome',
            'customers',
            'salesTransactions',
            'onlineUsers',
            'authUsers',
            'startOfWeek',
            'endOfWeek',
            'labels',
            'salesData',
            'expensesData'
        ]));
    }

    public function usersManager()
    {
        $cities = DB::table('city')
            ->select([
                'id',
                'name'
            ])
            ->where('soft_delete', 0)
            ->get();

        $stakeholderCategory = DB::table('stakeholder_category')
            ->select([
                'name',
                'id',
            ])
            ->where('soft_delete', 0)
            ->orderBy('name', 'ASC')
            ->get();

        $customerGroups = DB::table('customer_groups')
            ->select('*')
            ->where('soft_delete', 0)
            ->orderBy('name', 'ASC')
            ->get();

        $identifications = DB::table('identification_source')
            ->select('*')
            ->where('soft_delete', 0)
            ->orderBy('name', 'ASC')
            ->get();

        $stakeholders = DB::table('stakeholders AS STH')
            ->join('city AS C', 'STH.region_id', '=', 'C.id')
            ->select([
                'STH.name AS name',
                'STH.phone AS phone',
                'STH.address AS address',
                'STH.email AS email',
                'STH.tin AS tin',
                'STH.vrn AS vrn',
                'C.name AS region',
            ])
            ->where('STH.soft_delete', 0)
            ->orderBy('STH.id', 'DESC')
            ->get();

        return view('templates.users', compact('cities', 'stakeholderCategory', 'customerGroups', 'identifications', 'stakeholders'));
    }

    public function customerGroupd(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
        ]);

        $existingGroup = DB::table('customer_groups')
            ->where('name', $request->name)
            ->where('soft_delete', 0)
            ->exists();

        if ($existingGroup == true) {
            return redirect()->back()->with('error_msg', 'Group name' . ' ' . $request->name . ' ' . 'is already in the our database, try anaother name!');
        }

        DB::table('customer_groups')->insert([
            'name' => $request->name,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        return redirect()->back()->with('success_msg', 'Group named' . ' ' . $request->name . ' ' . 'added successfully in our database!');
    }

    public function storeStakeholder(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'phone' => 'required|string',
            'address' => 'nullable|string',
            'email' => 'nullable|string',
            'tin' => ['nullable', 'regex:/^\d{3}-\d{3}-\d{3}$/'],
            'vrn' => ['nullable', 'digits:10'],
            'region_id' => 'nullable|integer',
            'stakeholder_category' => 'nullable|integer',
            'customer_type' => 'nullable|string',
            'identification_type' => 'nullable|string',
            'identification_number' => 'nullable|string',
            'customer_group' => 'nullable|integer',
            'regulator_type' => 'nullable|string',
            'supplier_type' => 'nullable|string',
        ]);

        $existingStakeholder = DB::table('stakeholders')
            ->where('phone', $request->phone)
            ->orWhere('tin', $request->tin)
            ->orWhere('vrn', $request->vrn)
            ->where('soft_delete', 0)
            ->exists();

        if ($existingStakeholder == true) {
            return redirect()->back()->with('error_msg', 'These information already exist in our database!');
        }

        // dd($request->all());

        try {
            Stakeholder::create($validatedData);
        } catch (\Throwable $th) {
            return $th->getMessage();
        }

        return redirect()->back()->with('success_msg', 'New stakeholder added successfully!');
    }

    public function newBank()
    {
        $regions = DB::table('city')
            ->select([
                'name',
                'id'
            ])
            ->where('soft_delete', 0)
            ->get();

        // dd($regions);

        $bankLists = DB::table('banks AS B')
            ->join('city AS C', 'B.region', '=', 'C.id')
            ->select([
                'B.bank_name AS bankName',
                'B.account_name AS accountName',
                'B.account_number AS accountNumber',
                'B.phone AS phone',
                'B.address AS address',
                'B.box AS box',
                'B.bank_code AS code',
                'C.name AS region',
                'B.id AS autoId',
            ])
            ->where('B.soft_delete', 0)
            ->orderBy('B.bank_name', 'ASC')
            ->get();

        $bankBranchLists = DB::table('bank_branch AS BR')
            ->join('banks AS B', 'BR.bank_name', '=', 'B.id')
            ->select('BR.*', 'B.bank_name AS bank_name')
            ->where('BR.soft_delete', 0)
            ->get();

        // dd($bankBranchLists);

        return view('templates.new-bank', compact('regions', 'bankLists', 'bankBranchLists'));
    }

    public function createBank(Request $request)
    {
        $validatedData = $request->validate([
            'bank_name' => 'required|string',
            'account_name' => 'nullable|string',
            'phone' => 'nullable|string|max:10',
            'account_number' => 'nullable|string',
            'address' => 'nullable|string',
            'email' => 'nullable|string',
            'box' => 'nullable|string',
            'bank_code' => 'nullable|string',
            'region' => 'nullable|integer',
        ]);

        $bankExists = DB::table('banks')
            ->where('bank_name', $request->bank_name)
            ->where('account_number', $request->account_number)
            ->where('soft_delete', 0)
            ->exists();

        if ($bankExists == true) {
            return redirect()->back()->with('error_msg', 'Bank information already exists in our databases!');
        }

        try {
            Bank::create($validatedData);
        } catch (\Throwable $th) {
            return $th->getMessage();
        }

        return redirect()->back()->with('success_msg', 'New bank added successsfully!');
    }

    public function registerBranch(Request $request)
    {
        $request->validate([
            'bank_name' => 'required|integer',
            'branch_name' => 'required|string',
            'branch_code' => 'nullable|string',
        ]);

        $branchExsist = DB::table('bank_branch')
            ->where('branch_name', $request->branch_name)
            ->where('soft_delete', 0)
            ->exists();

        // dd($branchExsist);

        if ($branchExsist == true) {
            return redirect()->back()->with('error_msg', 'Branch information already exists in our databases!');
        }

        DB::table('bank_branch')->insert([
            'bank_name' => $request->bank_name,
            'branch_name' => $request->branch_name,
            'branch_code' => $request->branch_code,
            'added_by' => Auth::user()->id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        return redirect()->back()->with('success_msg', 'New bank branch added successsfully!');
    }

    public function stakeholdReports()
    {
        $customersReports = DB::table('stakeholders AS SH')
            ->join('city AS C', 'SH.region_id', '=', 'C.id')
            ->join('customer_groups AS CG', 'SH.customer_group', '=', 'CG.id')
            ->join('invoice AS I', 'SH.id', '=', 'I.customer_id')
            ->select([
                'SH.id',
                'SH.name',
                'SH.phone',
                'SH.address',
                'SH.tin',
                'SH.vrn',
                'SH.customer_type',
                'SH.email',
                'C.name AS region',
                'CG.name AS groupName',
                DB::raw('COUNT(I.id) AS invoiceIssued'),
            ])
            ->where('SH.stakeholder_category', 1)
            ->where('SH.soft_delete', 0)
            ->groupBy('SH.id', 'SH.phone', 'SH.address', 'SH.tin', 'SH.vrn', 'SH.customer_type', 'SH.name', 'SH.email', 'C.name', 'CG.name')
            ->get();

        $stakeholdersSuppliers = DB::table('stakeholders')
            ->select([
                'name',
                'email',
                'phone',
                'address',
                'tin',
                'vrn'
            ])
            ->where('stakeholder_category', 2)
            ->where('soft_delete', 0)
            ->get();

        $regulatorsReports = DB::table('stakeholders')
            ->select([
                'name',
                'email',
                'phone',
                'address',
            ])
            ->where('stakeholder_category', 3)
            ->where('soft_delete', 0)
            ->get();

        $bankReports = DB::table('banks AS B')
            ->join('bank_branch AS BR', 'B.id', 'BR.bank_name')
            ->join('city AS C', 'B.region', '=', 'C.id')
            ->select([
                'B.bank_name AS bankName',
                'BR.branch_name AS branchName',
                'B.address AS bankAddress',
                'B.phone AS bankPhone',
                'B.box AS bankBox',
                'B.bank_code AS bankCode',
                'B.account_name AS accountName',
                'B.account_number AS accNumber',
                'C.name AS region'
            ])
            ->where('B.soft_delete', 0)
            ->where('BR.soft_delete', 0)
            ->get();

        // dd($bankReports);

        return view('templates.stakeholder-reports', compact(
            'customersReports',
            'stakeholdersSuppliers',
            'regulatorsReports',
            'bankReports'
        ));
    }

    public function staffManagement()
    {
        $userRoles = DB::table('user_roles')
            ->select([
                'id',
                'name',
            ])
            ->orderBy('name', 'ASC')
            ->get();

        $departments = DB::table('departments')
            ->select([
                'id',
                'name',
                'code',
            ])
            ->orderBy('name', 'ASC')
            ->get();

        $employees = DB::table('emplyees AS E')
            ->join('departments AS D', 'E.department', '=', 'D.id')
            ->select([
                'E.id AS id',
                'E.first_name AS fName',
                'E.last_name AS lName',
                'E.phone_number AS phone_number',
                'E.emergency_contact_phone AS emergency_contact_phone',
                'D.name AS department',
                'E.address AS address',
                'E.tax_identification_number AS tin',
                'E.bank_name AS bankName',
                'E.bank_account_number AS bank_account_number',
            ])
            ->where('E.soft_delete', 0)
            ->orderBy('E.first_name', 'ASC')
            ->get();

        return view('templates.staff-management', compact('userRoles', 'departments', 'employees'));
    }

    public function storeStaff(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'middle_name' => 'nullable|string',
            'gender' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'national_id_number' => 'nullable|digits:20',
            'email' => 'required|string',
            'phone_number' => 'nullable|string',
            'emergency_contact_name' => 'nullable|string',
            'emergency_contact_phone' => 'nullable|string',
            'job_title' => 'nullable|string',
            'date_hired' => 'nullable|string',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'region' => 'nullable|string',
            'role' => 'nullable|integer',
            'country' => 'nullable|string',
            'postal_code' => 'nullable|string',
            'bank_name' => 'nullable|string',
            'bank_account_number' => 'nullable|string',
            'tax_identification_number' => 'nullable|digits:9',
            'social_security_number' => 'nullable|string',
            'social_security_name' => 'nullable|string',
            'employment_type' => 'nullable|string',
            'department' => 'nullable|integer',
        ]);

        $staffExists = DB::table('emplyees')
            ->where('phone_number', $request->phone_number)
            ->where('email', $request->email)
            ->exists();

        if ($staffExists == true) {
            return redirect()->back()->with('error_msg', 'This user is already in the database!');
        }

        $userData = Employees::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'middle_name' => $request->middle_name,
            'gender' => $request->gender,
            'date_of_birth' => $request->date_of_birth,
            'national_id_number' => $request->national_id_number,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'emergency_contact_name' => $request->emergency_contact_name,
            'emergency_contact_phone' => $request->emergency_contact_phone,
            'job_title' => $request->job_title,
            'date_hired' => $request->date_hired,
            'address' => $request->address,
            'city' => $request->city,
            'region' => $request->region,
            'country' => $request->country,
            'postal_code' => $request->postal_code,
            'bank_name' => $request->bank_name,
            'bank_account_number' => $request->bank_account_number,
            'tax_identification_number' => $request->tax_identification_number,
            'social_security_number' => $request->social_security_number,
            'role' => $request->role,
            'social_security_name' => $request->social_security_name,
            'employment_type' => $request->employment_type,
            'department' => $request->department,
            'created_by' => Auth::user()->id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('auth')->insert([
            'user_id' => $userData->id,
            'username' => $userData->phone_number,
            'password' => null,
            'role_id' => $userData->role,
            'login_attempts' => 0,
            'blocked_at' => null,
            'is_online' => false,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        return redirect()->back()->with('success_msg', 'New employee created_successfully!');
    }
}
