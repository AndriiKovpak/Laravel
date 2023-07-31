<?php

namespace App\Models;

class Session extends BaseModel
{
    /**
     * The integer id for the session.
     *
     * @var int
     */
    private $id;

    /**
     * The integer id for the user logged into the session.
     *
     * @var int
     */
    private $user_id;

    /**
     * IP address from which the user is connecting
     *
     * @var string
     */
    private $ip_address;

    /**
     * The browser info
     *
     * @var string
     */
    private $user_agent;

    /**
     * Encrypted session data
     *
     * @var string
     */
    private $payload;

    /**
     * Integer timestamp of when the session was last accessed
     *
     * @var int
     */
    private $last_activity;

    /**
     * When did the session start?
     *
     * @var string
     */
    private $BeginDate;

    /**
     * When does the session expire?
     *
     * @var string
     */
    private $EndDate;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'Sessions';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [ 'user_id', 'ip_address', 'user_agent', 'payload',
        'last_activity', 'BeginDate', 'EndDate'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'BeginDate' =>  'datetime',
        'EndDate'   =>  'datetime'
    ];

    /**
     * Return the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function User()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id', 'UserID');
    }
}
