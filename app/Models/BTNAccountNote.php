<?php

namespace App\Models;

class BTNAccountNote extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'BTNAccountNotes';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'BTNAccountNoteID';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'BTNAccountID', 'Note', 'UpdatedByUserID'
    ];

    /**
     * Return the BTNAccount
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function BTNAccount()
    {
        return $this->belongsTo(\App\Models\BTNAccount::class, 'BTNAccountID', 'BTNAccountID');
    }

    /**
     * Return the users that updated an Order.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function UpdatedByUser()
    {
        return $this->hasOne(\App\Models\User::class, 'UserID', 'UpdatedByUserID');
    }

    /**
     * Return the Note
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getAttribute('Note');
    }
}
