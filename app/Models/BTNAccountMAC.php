<?php

namespace App\Models;

class BTNAccountMAC extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'BTNAccountMACs';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'BTNMACID';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'BTNAccountID', 'OrderNum', 'MACType', 'CarrierOrder', 'Description', 'ContactName', 'ContactPhone',
        'ContactPhoneExt', 'RequestorName', 'CarrierDueDate', 'TelcoOrderNum', 'ContractDate', 'ContractExpDate',
        'RequestedContractRenewalDate', 'DisconnectRequestDate', 'DisconnectDate', 'ContractTerm', 'FinalCreditAmount',
        'UpdatedByUserID'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'CarrierDueDate'    =>  'datetime',
        'ContractDate'      =>  'datetime',
        'ContractExpDate'   =>  'datetime',
        'DisconnectDate'    =>  'datetime',
        'RequestedContractRenewalDate'  =>  'datetime',
        'DisconnectRequestDate'         =>  'datetime'
    ];

    /**
     * Return the CircuitMACNotes for this CircuitMAC
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function Notes()
    {
        return $this->hasMany(\App\Models\BTNAccountMACNote::class, 'BTNMACID', 'BTNMACID');
    }

    /**
     * Return the Circuit
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function BTNAccount()
    {
        return $this->belongsTo(\App\Models\BTNAccount::class, 'BTNAccountID', 'BTNAccountID');
    }

    /**
     * Return the MACType
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function Type()
    {
        return $this->belongsTo(\App\Models\MACType::class, 'MACType', 'MACType');
    }

    /**
     * Return the users that updated an InvoiceAP
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function UpdatedByUser()
    {
        return $this->hasOne(\App\Models\User::class, 'UserID', 'UpdatedByUserID');
    }

    public function getFinalCreditAmountAttribute($value) {
        return Util::formatCurrency($value);
    }
}
