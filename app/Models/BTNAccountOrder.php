<?php

namespace App\Models;

class BTNAccountOrder extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'BTNAccountOrders';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'BTNAccountOrderID';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'BTNAccountID','CarrierCircuitID', 'ACEITOrderNum', 'CarrierOrderNum', 'Note',
        'OrderStatus', 'UpdatedByUserID', 'OrderDate'
    ];

    protected $casts = [
        'OrderDate'      =>  'datetime',
    ];

    /**
     * Return the BTNAccountOrderFiles for this BTNAccountOrder
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function Files()
    {
        return $this->hasMany(\App\Models\BTNAccountOrderFile::class, 'BTNAccountOrderID', 'BTNAccountOrderID');
    }

    /**
     * Return the BTNAccount of this Order
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function BTN()
    {
        return $this->belongsTo(\App\Models\BTNAccount::class, 'BTNAccountID', 'BTNAccountID');
    }

    /**
     * Return the OrderStatusType
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function OrderStatusType()
    {
        return $this->belongsTo(\App\Models\OrderStatusType::class, 'OrderStatus', 'OrderStatus');
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
     * Approve this Order
     *
     * @return bool
     */
    public function approve()
    {
        return
            $this->update([
            'OrderStatus'   =>  OrderStatusType::STATUS_APPROVED
        ]);
    }

    /**
     * Check if Order is approved
     *
     * @return bool
     */
    public function isApproved()
    {
        return $this->getAttribute('OrderStatus') == OrderStatusType::STATUS_APPROVED;
    }
}
