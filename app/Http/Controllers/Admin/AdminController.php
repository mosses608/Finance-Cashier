<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class AdminController extends Controller
{
    //
    public function adminDashboard()
    {
        $adminData = DB::table('admins')->where('id', Auth::user()->user_id)->first();

        $companiesCounter = DB::table('companies')
            ->where('soft_delete', 0)
            ->count();

        $activeCompanies = DB::table('companies')
            ->where('status', 1)
            ->where('soft_delete', 0)
            ->count();

        $inactiveCompanies = DB::table('companies')
            ->where('status', 2)
            ->where('soft_delete', 0)
            ->count();

        $usersCounter = DB::table('auth')
            ->where('status', 1)
            ->count();

        return view('admin.dashboard', compact([
            'adminData',
            'companiesCounter',
            'activeCompanies',
            'inactiveCompanies',
            'usersCounter'
        ]));
    }

    public function viewAccounts()
    {
        $activeCompanies = DB::table('administrators AS AD')
            ->join('companies AS CM', 'AD.company_id', '=', 'CM.id')
            ->join('city AS C', 'CM.region', '=', 'C.id')
            ->select([
                'AD.names AS managerName',
                'AD.email AS managerEmail',
                'AD.phone AS managerPhone',
                'CM.id AS company_id',
                'CM.company_reg_no',
                'CM.company_name',
                'CM.company_email',
                'CM.address',
                'CM.tin',
                'CM.vrn',
                'C.name AS region',
                'CM.created_at AS dateReg'
            ])
            ->where('AD.role_id', 2)
            ->where('CM.status', 1)
            ->orderByDesc('CM.created_at')
            ->get();

        $inactiveAccounts = DB::table('administrators AS AD')
            ->join('companies AS CM', 'AD.company_id', '=', 'CM.id')
            ->join('city AS C', 'CM.region', '=', 'C.id')
            ->select([
                'AD.names AS managerName',
                'AD.email AS managerEmail',
                'AD.phone AS managerPhone',
                'CM.id AS company_id',
                'CM.company_reg_no',
                'CM.company_name',
                'CM.company_email',
                'CM.address',
                'CM.tin',
                'CM.vrn',
                'C.name AS region',
            ])
            ->where('AD.role_id', 2)
            ->where('CM.status', 0)
            ->orderByDesc('CM.created_at')
            ->get();

        return view('companies.accounts', compact([
            'activeCompanies',
            'inactiveAccounts'
        ]));
    }

    public function suspendAccount(Request $request)
    {
        $request->validate([
            'company_id' => 'required|string',
        ]);

        $companyId = Crypt::decrypt($request->company_id);

        $decodedCompanyId = json_decode($companyId);

        $companyIsActive = DB::table('companies')
            ->where('status', 1)
            ->where('id', $decodedCompanyId)
            ->exists();

        if ($companyIsActive === false) {
            return redirect()->back()->with('error_msg', 'This account is already suspended!');
        }

        DB::table('companies')
            ->where('id', $decodedCompanyId)->update([
                'status' => 0,
            ]);

        return redirect()->back()->with('success_msg', 'Account suspended successfully!');
    }

    public function activateAccount(Request $request)
    {
        $request->validate([
            'company_id' => 'required|string',
        ]);

        $companyId = Crypt::decrypt($request->company_id);

        $decodedCompanyId = json_decode($companyId);

        $companyIsSuspended = DB::table('companies')
            ->where('status', 0)
            ->where('id', $decodedCompanyId)
            ->exists();

        if ($companyIsSuspended === false) {
            return redirect()->back()->with('error_msg', 'This account is already active!');
        }

        DB::table('companies')
            ->where('id', $decodedCompanyId)->update([
                'status' => 1,
            ]);

        return redirect()->back()->with('success_msg', 'Account activated successfully!');
    }

    public function logs()
    {
        return view('companies.logs');
    }

    public function userAccounts()
    {
        return view('companies.user-accounts');
    }
}
