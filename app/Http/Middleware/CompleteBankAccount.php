<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class CompleteBankAccount
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
        $url = 'member/profile_bank';
        $complete = true;

        if(!Auth::user()->bankAccount) { $complete = false; }

        if(!$complete) {
            if($request->is('member/loans/accept/*')) {
                $backUrl = $request->path();
            } else {
                $backUrl = request()->headers->get('referer');
            }
            $request->session()->put('backUrl', $backUrl);
            return redirect($url)->with('error','Harap lengkapi informasi Bank Anda sebelum melanjutkan');
        }

        return $next($request);
    }
}
