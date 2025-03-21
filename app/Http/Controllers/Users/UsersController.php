<?php

namespace App\Http\Controllers\Users;

use App\Models\Role;
use App\Models\User;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;

class UsersController extends Controller
{
    //
    public function users(){
        $users = User::orderBy('id','asc')->filter(request(['search']))->paginate(15);
        $roles = Role::all();
        $departments = Department::orderBy('name','asc')->get();
        
        return view('inc.users', compact('roles','departments','users'));
    }

    public function storeUsers(Request $request){
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

        if($userDetails['password'] != $userDetails['password_confirmation']){

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
            'name','role_id','email','phone','department_id','username','password','image',
        ])); 
        // dd($request->all());
        return redirect()->back()->with('success_msg', 'User registered successfully!');
    }

    public function updateUser(Request $request, User $user){
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

        return redirect()->back()->with('success_msg','User updated successfully!');
    }

    public function deleteUser(Request $request, User $user){
        $user->delete();

        return redirect()->back()->with('success_msg','User deleted successfully!');
    }
}
