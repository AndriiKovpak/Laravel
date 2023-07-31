<?php

namespace App\Models;

class BTNAccountCarrierDetailNote extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'BTNAccountCarrierDetailNotes';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'BTNAccountCarrierDetailNoteID';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'BTNAccountID', 'DetailNotes','UpdatedByUserID'
    ];

    /**
     * Return the BTNAccount
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function CarrierDetails()
    {
        return $this->belongsTo(\App\Models\BTNAccountCarrierDetails::class, 'BTNAccountID', 'BTNAccountID');
    }

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
     * Return the Note
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getAttribute('DetailNotes') . '';
    }
}
