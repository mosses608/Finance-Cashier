<?php

namespace App\Http\Controllers\Authenticates;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\ResetPasswordMail;
use App\Models\PasswordResetToken;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthenticateController extends Controller
{
    //
    public function authentication(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'password' => 'required|string|max:255|min:5'
        ]);

        $user = User::where('username', $request->username)->first();

        if (!$user) {
            return redirect()->back()->with('error_msg', 'User does not exist!');
        }

        $company_id = $user->company_id;

        $isCompanyActive = DB::table('companies')->where('id', $company_id)
            ->where('status', 1)
            ->where('soft_delete', 0)
            ->exists();

        if ($isCompanyActive == false) {
            return redirect()->back()->with('error_msg', 'This company account is temporarily deactivated, please contact our help desk for more information!');
        }

        if ($user->blocked_at && Carbon::now()->lt(Carbon::parse($user->blocked_at)->addMinutes(30))) {
            return back()->with('error_msg', 'Your account is temporarily blocked due to too many login attempts!');
        }

        if (Hash::check($request->password, $user->password)) {
            $user->update([
                'login_attempts' => 0,
                'blocked_at' => null,
            ]);

            Auth::login($user);

            DB::table('auth')->where('user_id', Auth::user()->user_id)->update([
                'is_online' => 1,
            ]);

            $companyId = Auth::user()->company_id;

            $modules = DB::table('auth_user_modules AS AUM')
                ->join('company_modules AS CM', 'AUM.id', '=', 'CM.parent_module_id')
                ->select([
                    'AUM.module_parent_id AS module_parent_id',
                    'AUM.module_name AS module_name',
                    'AUM.module_path AS module_path',
                    'AUM.module_icon AS module_icon',
                    'CM.parent_module_id AS module_id',
                ])
                ->whereNull('AUM.is_admin')
                ->where('CM.company_id', $companyId)
                ->get()
                ->unique('module_id');

            if ($modules->isEmpty()) {
                return redirect()->route('modules')->with('success_msg', 'Logged in successfully, but your dashboard looks empty. Please select at least one feature to use in Akili Soft ERP, it is free!');
            }

            $companyHasLogo = DB::table('companies')
                ->select([
                    'logo'
                ])
                ->where('id', $companyId)
                ->first();

            if($companyHasLogo->logo == null){
                return redirect()->route('upload.logo');
            }

            return redirect()->route('home')->with('success_msg', 'Logged in successfully!');
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

    public function forgotPass()
    {
        return view('inc.forgot-password');
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255',
        ]);

        $user = User::where('username', $request->input('username'))->first();

        if (!$user) {
            return redirect()->back()->with('error_msg', 'User does not exist!');
        }

        $email = DB::table('administrators')->where('id', $user->user_id)
            ->select([
                'email',
            ])->value('email');

        if (!$email) {
            return redirect()->back()->with('error_msg', 'Email was not found in our database!, please contact your administrator');
        }

        $userEmail = $email;

        $token = Str::random(60);
        $user->update(['reset_token' => $token]);

        Mail::to($userEmail)->send(new ResetPasswordMail($user->username, $token));

        $maskedEmail = substr($userEmail, 0, 4) . '******' . substr($userEmail, -10);

        PasswordResetToken::create([
            'email' => $userEmail,
            'token' => $token,
        ]);

        return redirect()->back()->with('success_msg',  'A password reset link has been sent to your email: ' . $maskedEmail);
    }

    public function resetMail(Request $request)
    {
        $token = $request->query('token');
        $username = $request->query('username');
        return view('inc.reset_password', compact('username', 'token'));
    }

    public function finaliseReset(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'password_comfirm' => 'required|string|max:255',
            'password' => 'required|string|max:255',
        ]);

        if ($request->username == '') {
            return redirect()->back()->with('error_msg', 'Username not found, Try again later!');
        }

        if ($request->password != $request->password_comfirm) {
            return redirect()->back()->with('error_msg', 'Passwords do not match!');
        }

        $user = User::where('username', $request->input('username'))->first();

        if (!$user) {
            return redirect()->back()->with('error_msg', 'Error!');
        }

        if (Hash::check($request->password, $user->password)) {
            return redirect()->back()->with('success_msg', 'New password can not be same as old password!');
        }

        $user->update([
            'password' => Hash::make($request->password),
            'reset_token' => null,
        ]);

        return redirect('/')->with('success_msg', 'Password updated successfully. You can login!');
    }

    public function logout(Request $request)
    {
        DB::table('auth')->where('user_id', Auth::user()->user_id)->update([
            'is_online' => 0,
        ]);

        $request->session()->invalidate();
        Auth::logout();
        return redirect()->route('login')->with('success_msg', 'Logged out sucessfully!');
    }
}
