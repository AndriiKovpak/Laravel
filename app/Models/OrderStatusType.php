<?php

namespace App\Models;

class OrderStatusType extends BaseModel
{
    const STATUS_PENDING    =   1;
    const STATUS_APPROVED   =   2;
    const STATUS_DECLINED   =   3;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'OrderStatusTypes';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'OrderStatus';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['OrderStatusName'];

    /**
     * Do not use timestamps in here.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Return the BTNAccountOrders for this OrderStatusType
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function BTNAccountOrders()
    {
        return $this->hasMany(\App\Models\BTNAccountOrder::class, 'OrderStatus', 'OrderStatus');
    }

    /**
     * Return the Order Status Name
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getAttribute('OrderStatusName');
    }

    /**
     * Chech if the Status equals to DECLINED
     *
     * @return bool
     */
    public function isDeclined()
    {
        return $this->getAttribute('OrderStatus') == self::STATUS_DECLINED;
    }
}
