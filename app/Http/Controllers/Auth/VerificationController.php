<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\User;
use App\VerificationToken;
use App\Events\UserRequestedVerificationEmail;

class VerificationController extends Controller
{
    public function verify(VerificationToken $token)
    {
    	$token->user()->update([
			'active' => true
		]);
        $user = User::find($token->user_id);
        // dd($user);
        activity()
            ->causedBy($user)
            ->log('Activated');
		$token->delete();
		return redirect('/login')->with('success','Verifikasi akun berhasil, silahkan login kembali');
    }

    public function resend()
    {
        return view('auth.verify_resend');
    }
 
    public function resend_email(Request $request)
    {
    	$user = User::where('email', $request->email)->first();
    	if($user) {
    		if($user->isActive) {
	            return redirect('/login')->with('message','Akun Anda sudah aktif');
	        } else {
                $token = $user->verificationToken()->create([
                    'token' => bin2hex(random_bytes(32))
                ]);
                event(new UserRequestedVerificationEmail($user));
                return redirect('/login')->with('success','Email berhasil dikirim, harap periksa kotak masuk Anda');
            }
    	} else {
    		return back()->with('error','Alamat email tidak ditemukan');
    	}	
    }

}
