<?php

namespace App\Http\Controllers\Authenticates;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AuthenticateController extends Controller
{
    //
    public function authentication(Request $request){
        $userCredentials = $request->validate([
            'username' => 'required|string|max:255',
            'password' => 'required|string|max:255|min:5'
        ]);

        if(Auth::guard('web')->attempt($userCredentials)){
            $request->session()->regenerateToken();
            return redirect('/dashboard')->with('success_msg','Logged in successfully!');
        }else{
            return redirect()->back()->with('error_msg','Incorrect username or password!');
        }
    }

    public function logout(Request $request){
        $request->session()->invalidate();
        return redirect('/')->with('success_msg','Logged out sucessfully!');
    }
}
