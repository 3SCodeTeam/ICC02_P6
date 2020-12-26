<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SessionControl
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $role='student')
    {
        if (is_null($request->session()->get('sql_user_id'))) {
            //return view('login', ['msg'=>'Debes iniciar sesión.']);
            return redirect()->route('login', );
        }
        switch ($request->session()->get('user_role')){
            case 'admin': return $next($request);
            case 'teacher': if($role === 'teacher'){
                return $next($request);
            };
            break;
            case 'student': if($role === 'student'){
                return $next($request);
            }
        }
        return redirect()->route('login', );
    }
}
