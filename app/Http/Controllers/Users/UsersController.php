<?php

namespace App\Http\Controllers\Users;

use Carbon\Carbon;
use App\Models\Role;
use App\Models\User;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    //
    public function users()
    {
        $users = User::orderBy('id', 'asc')->filter(request(['search']))->paginate(15);
        $roles = Role::all();
        $departments = Department::orderBy('name', 'asc')->get();

        return view('inc.users', compact('roles', 'departments', 'users'));
    }

    public function storeUsers(Request $request)
    {
        $userDetails = $request->validate([
            'name' => 'required|string|max:255',
            'role_id' => 'required|integer',
            'email' => 'nullable|string|max:30',
            'phone' => 'nullable|string|max:13',
            'department_id' => 'required|integer',
            'username' => 'required|string|max:255',
            'password' => 'required|string|max:255',
            'password_confirmation' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ]);

        $userDetails['password'] == $userDetails['password_confirmation'];

        if ($userDetails['password'] != $userDetails['password_confirmation']) {

            return redirect()->back()->with('error_msg', 'Passwords do not match!');
        }

        $userDetails['password'] = bcrypt($userDetails['password']);

        if ($request->hasFile('image')) {
            $userDetails['image'] = $request->file('image')->store('profiles', 'public');
        }

        $existingUser = User::where('username', $request->input('username'))->first();

        if ($existingUser) {
            return redirect()->back()->with('error_msg', 'User already exists!');
        }

        User::create($request->only([
            'name',
            'role_id',
            'email',
            'phone',
            'department_id',
            'username',
            'password',
            'image',
        ]));
        // dd($request->all());
        return redirect()->back()->with('success_msg', 'User registered successfully!');
    }

    public function updateUser(Request $request, User $user)
    {
        $userInfo = $request->validate([
            'name' => 'required',
            'role_id' => 'required',
            'email' => 'nullable',
            'phone' => 'nullable',
            'department_id' => 'required',
            'username' => 'required',
            'password' => 'required',
            'image' => 'nullable',
        ]);

        if ($request->hasFile('image')) {
            $userDetails['image'] = $request->file('image')->store('profiles', 'public');
        }

        $user->updateUser($userInfo);

        return redirect()->back()->with('success_msg', 'User updated successfully!');
    }

    public function deleteUser(Request $request, User $user)
    {
        $user->delete();

        return redirect()->back()->with('success_msg', 'User deleted successfully!');
    }

    public function systemUsers()
    {
        // $companyId = DB::table('companies AS C')
        //     ->join('administrators AS A', 'C.id', '=', 'A.company_id')
        //     ->select('C.id AS companyId')
        //     ->where('A.phone', Auth::user()->username)
        //     ->orWhere('A.email', Auth::user()->username)
        //     ->first();

        $companyId = Auth::user()->company_id;

        $employees = DB::table('emplyees')
            ->select([
                'id',
                'phone_number',
                'first_name',
                'last_name'
            ])
            ->where('company_id', $companyId)
            ->orderBy('first_name', 'ASC')
            ->get();

        $systemUsersFromAdmin = DB::table('administrators AS AD')
            ->join('auth AS AU', 'AD.id', '=', 'AU.user_id')
            ->join('user_roles AS UR', 'AD.role_id', '=', 'UR.id')
            ->select([
                'AD.names AS fullNames',
                'AU.username AS username',
                'UR.name AS roleName',
                'AU.status AS status',
            ])
            ->where('AD.company_id', $companyId)
            ->get();

        // dd($systemUsersFromAdmin);

        $systemUsersFromEmploy = DB::table('emplyees AS EMP')
            ->join('auth AS AU', 'EMP.id', '=', 'AU.user_id')
            ->join('user_roles AS UR', 'EMP.role', '=', 'UR.id')
            ->join('departments AS DP', 'EMP.department', '=', 'DP.id')
            ->select([
                'EMP.first_name AS fName',
                'EMP.last_name AS lName',
                'AU.username AS username',
                'UR.name AS roleName',
                'DP.name AS department',
                'AU.status AS status',
            ])
            ->where('EMP.company_id', $companyId)
            ->get();

        return view('users.system-users', compact([
            'employees',
            'systemUsersFromAdmin',
            'systemUsersFromEmploy'
        ]));
    }

    public function addSystemUsers(Request $request)
    {
        $request->validate([
            'role_id' => 'required|integer',
            'names' => 'nullable|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|string',
            'username' => 'required|string',
            'password' => 'required|string',
            'password_confirm' => 'required|string',
            'user_id' => 'nullable|integer',
        ]);

        if ($request->password != $request->password_confirm) {
            return redirect()->back()->with('error_msg', 'Passwords do not match!');
        }

        $userExsist = DB::table('auth')
            ->where('username', $request->username)
            ->orWhere('user_id', $request->user_id)
            ->exists();

        if ($userExsist == true) {
            return redirect()->back()->with('error_msg', 'This user already has access to this system!');
        }

        $companyId = Auth::user()->company_id;

        if ($request->has('role_id') && $request->role_id == 1) {

            $userId = DB::table('administrators')->insertGetId([
                'names' => $request->names,
                'role_id' => $request->role_id,
                'email' => $request->email ?? null,
                'phone' => $request->phone,
            ]);

            DB::table('auth')->insert([
                'user_id' => $userId,
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'role_id' => $request->role_id,
                'company_id' => $companyId,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            return redirect()->back()->with('success_msg', 'User added successfully!');
        }

        if ($request->has('role_id') && $request->role_id == 2) {
            DB::table('auth')->insert([
                'user_id' => $request->user_id,
                'username' => $request->username,
                'company_id' => $companyId,
                'password' => Hash::make($request->password),
                'role_id' => $request->role_id,
            ]);

            return redirect()->back()->with('success_msg', 'User added successfully!');
        }

        return redirect()->back()->with('error_msg', 'Failed to add new user!');
    }

    public function passwordRest()
    {
        $companyId = Auth::user()->company_id;

        $users = DB::table('auth AS A')
            ->join('administrators AS AD', 'A.user_id', '=', 'AD.id')
            ->join('emplyees AS EM', 'A.user_id', '=', 'EM.id')
            ->select('A.username', 'A.user_id')
            ->where('AD.company_id', $companyId)
            ->where('EM.company_id', $companyId)
            ->where('A.status', 1)
            ->get();

        return view('users.password-resets', compact('users'));
    }

    public function resetPasswords(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'password' => 'required|string',
            'password_confirm' => 'required|string',
        ]);

        $userExists = DB::table('auth')
            ->where('user_id', $request->user_id)
            ->exists();

        if ($userExists == false) {
            return redirect()->back()->with('success_msg', 'User does not exists!');
        }

        if ($request->password != $request->password_confirm) {
            return redirect()->back()->with('error_msg', 'Passwords do not match!');
        }

        DB::table('auth')->where('user_id', $request->user_id)
            ->update([
                'password' => Hash::make($request->password),
            ]);

        return redirect()->back()->with('success_msg', 'Passwords changed successfully!');
    }

    public function usersReports()
    {
        $companyId = Auth::user()->company_id;

        $systemUsersFromAdmin = DB::table('administrators AS AD')
            ->join('auth AS AU', 'AD.id', '=', 'AU.user_id')
            ->join('user_roles AS UR', 'AD.role_id', '=', 'UR.id')
            ->select([
                'AD.names AS fullNames',
                'AU.username AS username',
                'UR.name AS roleName',
                'AU.status AS status',
            ])
            ->where('AD.company_id', $companyId)
            ->get();

        $systemUsersFromEmploy = DB::table('emplyees AS EMP')
            ->join('auth AS AU', 'EMP.id', '=', 'AU.user_id')
            ->join('user_roles AS UR', 'EMP.role', '=', 'UR.id')
            ->join('departments AS DP', 'EMP.department', '=', 'DP.id')
            ->select([
                'EMP.first_name AS fName',
                'EMP.last_name AS lName',
                'AU.username AS username',
                'UR.name AS roleName',
                'DP.name AS department',
                'AU.status AS status',
            ])
            ->where('EMP.company_id', $companyId)
            ->get();

        $userLogs = DB::table('sessions AS S')
            ->join('auth AS A', 'S.user_id', '=', 'A.id')
            ->join('emplyees AS E', 'E.id', '=', 'A.user_id')
            ->select([
                'E.first_name AS fName',
                'E.last_name AS lName',
                'S.ip_address AS ipaddress',
                'S.user_agent AS agent',
            ])
            ->where('E.company_id', $companyId)
            ->orderBy('S.id', 'DESC')
            ->get();

        // dd($userLogs);

        $logs = DB::table('sessions AS S')
            ->join('auth AS A', 'S.user_id', '=', 'A.id')
            ->join('administrators AS E', 'E.id', '=', 'A.user_id')
            ->select([
                'E.names AS names',
                'S.ip_address AS ipaddress',
                'S.user_agent AS agent',
            ])
            ->where('E.company_id', $companyId)
            ->orderBy('S.id', 'DESC')
            ->get();

        // dd($logs);

        return view('users.reports', compact([
            'systemUsersFromAdmin',
            'systemUsersFromEmploy',
            'userLogs',
            'logs'
        ]));
    }
}
