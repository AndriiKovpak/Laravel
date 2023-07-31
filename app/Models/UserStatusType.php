<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserStatusType extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'UserStatusTypes';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'UserStatus';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'UserStatus', 'UserStatusName'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'UserStatus'    =>  'integer'
    ];

    /**
     * Return all the Users with this UserStatusType
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function Users()
    {
        return $this->hasMany(\App\Models\User::class, 'UserStatus', 'UserStatus');
    }

    /**
     * Return the string value
     *
     * @return mixed
     */
    public function __toString()
    {
        return $this->getAttribute('UserStatusName');
    }
}