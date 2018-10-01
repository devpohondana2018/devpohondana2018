<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class CompleteProfile
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
        $url = 'member/profile';
        $complete = true;

        if (!Auth::user()->home_address) { $complete = false; }
        if (!Auth::user()->home_city) { $complete = false; }
        if (!Auth::user()->home_state) { $complete = false; }
        if (!Auth::user()->home_postal_code) { $complete = false; }
        if (!Auth::user()->home_ownership) { $complete = false; }
        if (!Auth::user()->home_phone) { $complete = false; }
        if (!Auth::user()->mobile_phone) { $complete = false; }
        if (!Auth::user()->id_no) { $complete = false; }
        if (!Auth::user()->npwp_no) { $complete = false; }
        if (!Auth::user()->pob) { $complete = false; }
        if (!Auth::user()->dob) { $complete = false; }
        if (!Auth::user()->company_id) { $complete = false; }
        if (!Auth::user()->employment_salary) { $complete = false; }
        if (!Auth::user()->employment_position) { $complete = false; }
        if (!Auth::user()->employment_duration) { $complete = false; }
        if (!Auth::user()->employment_status) { $complete = false; }

        if(!$complete) { return redirect($url)->with('error','Harap lengkapi profil Anda sebelum melanjutkan'); }
        return $next($request);
    }
}
