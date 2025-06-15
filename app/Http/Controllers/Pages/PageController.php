<?php

namespace App\Http\Controllers\Pages;

use Carbon\Carbon;
use App\Models\Stakeholder;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

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

    public function testPage()
    {
        $totalCustomers = DB::table('customer')
            ->where('soft_delete', 0)
            ->count();
        return view('inc.home', compact('totalCustomers'));
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
            'tin' => ['required', 'regex:/^\d{3}-\d{3}-\d{3}$/'],
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
}
