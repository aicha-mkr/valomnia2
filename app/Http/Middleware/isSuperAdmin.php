<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class isSuperAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if($request->route()->named('auth-login') || $request->route()->named('post-login')) {
            if($request->route()->named('auth-login')){
                if(session()->has('user') && session()->has('token_user')){
                    if(session()->has('isOrganisation')){
                        return redirect()->route('dashboard-organisation');
                    }else{
                        return redirect()->route('dashboard-admin');
                    }
                }else{
                    return $next($request);
                }
            }else{
                return $next($request);
            }

        }else{
            if(session()->has('isSuperAdmin')){
                return $next($request);
            }else{
                return redirect()->route('auth-login')->with('error', 'Not authorized !');
            }
        }
    }
}
