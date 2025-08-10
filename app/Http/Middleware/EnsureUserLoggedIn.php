<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserLoggedIn
{   

    protected $except = [
        '/',                  
        '/users/verify',     
        '/logout',           
    ];
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($this->inExceptArray($request)) {
            return $next($request);
        }
        if (!Session::has('user_id')) {
            return redirect('/');
        }

        return $next($request);
    }

    protected function inExceptArray($request)
    {
        foreach ($this->except as $except) {
            if ($request->is(trim($except, '/'))) {
                return true;
            }
        }

        return false;
    }
}
