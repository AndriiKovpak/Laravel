<?php

namespace App\Models;

class CircuitMAC extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'CircuitMACs';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'CircuitMACID';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'CircuitID', 'OrderNum', 'MACType', 'CarrierOrder', 'Description', 'ContactName', 'ContactPhone',
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
        return $this->hasMany(\App\Models\CircuitMACNote::class, 'CircuitMACID', 'CircuitMACID');
    }

    /**
     * Return the Circuit
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function Circuit()
    {
        return $this->belongsTo(\App\Models\Circuit::class, 'CircuitID', 'CircuitID');
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
