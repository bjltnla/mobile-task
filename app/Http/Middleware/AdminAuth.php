<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class AdminAuth
{
    public function handle(Request $request, Closure $next)
    {
        try {
            $admin = JWTAuth::parseToken()->authenticate();
            if(!$admin){
                return redirect('/admin/login');
            }
        } catch (\Exception $e) {
            return redirect('/admin/login');
        }

        return $next($request);
    }
}
