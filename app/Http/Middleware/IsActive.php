<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class IsActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!$request->user()->isActive) {
            Auth::logout();
            return redirect('/login')->withMessage('Akun Anda belum aktif');
        }
        return $next($request);
    }
}
