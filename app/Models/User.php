<?php

namespace App\Models;

use Carbon\Carbon;

use Illuminate\Auth\Authenticatable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Notifications\Notifiable;
use App\Notifications\Auth\ResetPassword;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends BaseModel implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;
    use Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'Users';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'UserID';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'FirstName', 'LastName', 'UserName',
        'EmailAddress', 'PhoneNumber', 'PhoneExt',
        'Password', 'LastPasswordResetDate', 'InvalidLoginAttempts',
        'UserStatus', 'SecurityGroup',
        'UpdatedByUserID'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'UserStatus'            =>  'integer',
        'SecurityGroup'         =>  'integer',
        'InvalidLoginAttempts'  =>  'integer',
        'UpdatedByUserID'       =>  'integer',
        'LastPasswordResetDate' =>  'date'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'Password', 'RememberToken',
    ];

    /**
     * Save hash of password not the password itself.
     *
     * @param $value
     * @return $this
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['Password'] = Hash::make($value);

        $this->attributes['LastPasswordResetDate'] = Carbon::today();

        return $this;
    }

    /**
     * Return UserStatusType
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function Status()
    {
        return $this->hasOne(\App\Models\UserStatusType::class, 'UserStatus', 'UserStatus');
    }

    /**
     * Return the SecurityGroup
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function SecurityGroup()
    {
        return $this->hasOne(\App\Models\SecurityGroup::class, 'SecurityGroup', 'SecurityGroup');
    }

    /**
     * Return the UserPasswordResetTokens for this User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function PasswordResetTokens()
    {
        return $this->hasMany(\App\Models\UserPasswordResetToken::class, 'UserID', 'UserID');
    }

    /**
     * Return the DivisionDistricts for this User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function DivisionDistricts()
    {
        return $this->belongsToMany(
            \App\Models\DivisionDistrict::class,
            'Users_DivisionDistricts',
            'UserID',
            'DivisionDistrictID'
        );
    }

    /**
     * Return the Sessions for this User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function Sessions()
    {
        return $this->hasMany(\App\Models\Session::class, 'user_id', 'UserID');
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->getAttribute('Password');
    }

    /**
     * Get the e-mail address where password reset links are sent.
     *
     * @return mixed
     */
    public function getEmailForPasswordReset()
    {
        return $this->getAttribute('EmailAddress');
    }

    /**
     * Return configuration for notifications
     *
     * @param $driver
     * @return mixed|null
     */
    public function routeNotificationFor($driver)
    {
        switch ($driver) {

            case 'mail':
                return $this->getAttribute('EmailAddress');
        }

        return null;
    }

    /**
     * Send token to reset password
     *
     * @param string $token
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    /**
     * Get the column name for the "remember me" token.
     *
     * @return string
     */
    public function getRememberTokenName()
    {
        return 'RememberToken';
    }

    /**
     * Return full name of an User.
     *
     * @return string
     */
    public function getFullName()
    {
        return $this->getAttribute('FirstName') . ' ' . $this->getAttribute('LastName');
    }

    public function getFullNameAttribute()
    {
        return $this->getFullName();
    }

    /**
     * Return user Favorite Reports
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function favoriteReports()
    {
        return $this->belongsToMany(Report::class, 'Users_Reports', 'UserID', 'ReportID');
    }
}
