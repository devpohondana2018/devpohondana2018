<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use DB;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Cmgmyr\Messenger\Traits\Messagable;

class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;
    use Messagable;

    /**
     * The attributes that are guarded, else is mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function verificationToken()
    {
        return $this->hasOne('App\VerificationToken');
    }

    public function getIsActiveAttribute()
    {
        return $this->active;
    }

    public function getIsVerifiedAttribute()
    {
        return $this->verified;
    }

    public static function byEmail($email)
    {
        return static::where('email', $email);
    }

    public function loans()
    {
        return $this->hasMany('App\Loan');
    }

    public function investments()
    {
        return $this->hasMany('App\Investment');
    }

    public function bankAccount()
    {
        return $this->hasOne('App\BankAccount');
    }

    public function company()
    {
        return $this->belongsTo('App\Company','company_id');
    }

    public function transactions()
    {
        return $this->hasMany('App\Transaction');
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    
    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function logs()
    {
        return $this->morphMany('App\ActivityLog', 'causer');
    }

    /** Loan application grade helpers **/

    public function getCompletedLoansCountAttribute()
    {
        return $this->loans()->where('status_id', 7)->count();
    }

    public function getOwnsHouseAttribute()
    {
        if($this->home_ownership == 'sendiri') { return true; }
        return false;
    }

    public function getIsPermanentEmployeeAttribute()
    {
        if($this->employment_status == 'permanen') { return true; }
        return false;
    }

    public function getWorksInAffiliateCompanyAttribute()
    {
        if($this->company) {
            if($this->company->affiliate == TRUE) {
                return true;
            }
        }
        return false;
    }

    public function addCode(){
        $this->code = ( $this->hasRole('borrower') ? 'B' : 'L' ) . $this->id;
        $this->save();
    }

    protected static function boot() {
        parent::boot();

        DB::beginTransaction();
        User::deleting(function($user) {
            foreach ($user->transactions()->get() as $transaction) {
                $transaction->forceDelete();
            }
        });    
        User::deleting(function($user) { // before delete() method call this
            $user->bankAccount()->forceDelete();
             // do the rest of the cleanup...
        });        
        User::deleting(function($user) { // before delete() method call this
            foreach ($user->investments()->get() as $investment) {
                $investment->forceDelete();
            }
             // do the rest of the cleanup...
        });  
        User::deleting(function($user) {
            foreach ($user->logs()->get() as $log) {
                $log->forceDelete();
            }
        });        
        User::deleting(function($user) { // before delete() method call this
            foreach ($user->loans()->get() as $loan) {
                $loan->forceDelete();
            }             
             // do the rest of the cleanup...
        });        
        User::deleting(function($user) { // before delete() method call this
             $user->verificationToken()->forceDelete();
             // do the rest of the cleanup...
        });

        static::created(function($user) {
            $user->code = ( $user->hasRole('borrower') ? 'B' : 'L' ) . $user->id;
            $user->save();
        });   

        DB::commit();
    }
    /** End loan application grade helpers **/
}
