<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SessionTimeout
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $sessionLifetime = config('session.lifetime') * 60;
        
        if (session()->has('last_activity') && (time() - session('last_activity') > $sessionLifetime)) {
            Auth::logout();
            session()->flush();
            
            return redirect('/')->with('success_msg','You are logged out due to long time inactivity, Try to login again!');
        }
        
        session(['last_activity' => time()]);

        return $next($request);
    }
}
