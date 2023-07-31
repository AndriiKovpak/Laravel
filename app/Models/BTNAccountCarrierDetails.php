<?php

namespace App\Models;

class BTNAccountCarrierDetails extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'BTNAccountCarrierDetails';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'BTNAccountID';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'BillingURL', 'InvoiceAvailableDate', 'Username', 'Password', 'IsPaperless', 'PIN'
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
     * Return the BTNAccount
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function Notes()
    {
        return $this->hasMany(\App\Models\BTNAccountCarrierDetailNote::class, 'BTNAccountID', 'BTNAccountID');
    }
}
