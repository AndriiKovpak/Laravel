<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPasswordResetToken extends Model
{
    /**
     * The integer id for the token.
     *
     * @var int
     */
    private $ResetTokenID;

    /**
     * The integer id of the user
     *
     * @var int
     */
    private $UserID;

    /**
     * The token
     *
     * @var string
     */
    private $Token;

    /**
     * Date token was issued
     *
     * @var string
     */
    private $IssueDate;

    /**
     * Date token expires
     *
     * @var string
     */
    private $ExpireDate;

    /**
     * The UserID of the person who last updated the token
     *
     * @var int
     */
    private $UpdatedByUserID;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'UserPasswordResetTokens';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'ResetTokenID';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'UserID', 'Token', 'IssueDate', 'ExpireDate', 'UpdatedByUserID'
    ];

    /**
     * Return the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function User()
    {
        return $this->belongsTo(\App\Models\User::class, 'UserID', 'UserID');
    }
}
