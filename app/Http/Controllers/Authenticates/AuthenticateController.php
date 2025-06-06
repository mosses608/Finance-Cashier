<?php

namespace App\Http\Controllers\Authenticates;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\ResetPasswordMail;
use App\Models\PasswordResetToken;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthenticateController extends Controller
{
    //
    public function authentication(Request $request){
        $request->validate([
            'username' => 'required|string|max:255',
            'password' => 'required|string|max:255|min:5'
        ]);

        $user = User::where('username', $request->username)->first();

        // dd($user);

        if(!$user){
            return redirect()->back()->with('error_msg','User does not exist!');
        }

        if ($user->blocked_at && Carbon::now()->lt(Carbon::parse($user->blocked_at)->addMinutes(30))) {
            return back()->with('error_msg', 'Your account is temporarily blocked due to too many login attempts!');
        }

        if(Hash::check($request->password, $user->password)){
            $user->update([
                'login_attempts' => 0,
                'blocked_at' => null,
            ]);

            Auth::login($user);

            return redirect('/dashboard')->with('success_msg','Logged in successfully!');
        }

        $user->increment('login_attempts');
        $remainingAttempts = 3 - $user->login_attempts;

        if ($user->login_attempts >= 3) {
            $user->update(['blocked_at' => Carbon::now()]);
            return back()->with('error_msg', 'Too many failed login attempts. Your account is now blocked for 30 minutes.');
        }

        return back()->with('error_msg', 'Incorrect login credentials! ' . $remainingAttempts . ' attempt(s) left.');

        // if(Auth::guard('web')->attempt($userCredentials)){
        //     $request->session()->regenerateToken();
        //     return redirect('/dashboard')->with('success_msg','Logged in successfully!');
        // }else{
        //     return redirect()->back()->with('error_msg','Incorrect username or password!');
        // }
    }
    
    public function forgotPass(){
        return view('inc.forgot-password');
    }

    public function resetPassword(Request $request){
        $request->validate([
            'username' => 'required|string|max:255',
        ]);

        $user = User::where('username', $request->input('username'))->first();

        if(!$user){
            return redirect()->back()->with('error_msg','User does not exist!');
        }

        $userEmail = $user->email;

        $token = Str::random(60);
        $user->update(['reset_token' => $token]);

        Mail::to($userEmail)->send(new ResetPasswordMail($user->username, $token));

        $maskedEmail = substr($userEmail, 0,4) . '******' . substr($userEmail, -10);

        PasswordResetToken::create([
            'email' => $userEmail,
            'token' => $token,
        ]);

        return redirect('/')->with('success_msg',  'A password reset link has been sent to your email: ' . $maskedEmail);
    }

    public function resetMail(Request $request){
        $token = $request->query('token');
        $username = $request->query('username');
        return view('inc.reset_password', compact('username','token'));
    }

    public function finaliseReset(Request $request){
        $request->validate([
            'username' => 'required|string|max:255',
            'password_comfirm' => 'required|string|max:255',
            'password' => 'required|string|max:255',
        ]);

        if($request->username == ''){
            return redirect()->back()->with('error_msg','Username not found, Try again later!');
        }

        if($request->password != $request->password_comfirm){
            return redirect()->back()->with('error_msg','Passwords do not match!');
        }

        $user = User::where('username', $request->input('username'))->first();

        if(!$user){
            return redirect()->back()->with('error_msg','Error!');
        }

        if(Hash::check($request->password, $user->password)){
            return redirect()->back()->with('success_msg','New password can not be same as old password!');
        }

        $user->update([
            'password' => Hash::make($request->password),
            'reset_token' => null,
        ]);

        return redirect('/')->with('success_msg','Password updated successfully. You can login!');
    }

    public function logout(Request $request){
        $request->session()->invalidate();
        // Auth::logout();
        return redirect('/')->with('success_msg','Logged out sucessfully!');
    }
}
