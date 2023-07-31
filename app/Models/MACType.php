<?php

namespace App\Models;

class MACType extends BaseModel
{
    const GENERAL           =   1;
    const CONTRACT_INFO     =   2;
    const BILLING_DISPUTES  =   3;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'MACTypes';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'MACType';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['MACTypeName'];

    /**
     * Do not use timestamps
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Return the CircuitMACNotes for this CircuitMAC
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function CircuitMACs()
    {
        return $this->hasMany(\App\Models\CircuitMAC::class, 'MACType', 'MACType');
    }

    /**
     * Return the MAC Type Name
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getAttribute('MACTypeName');
    }

    /**
     * Check if type is General
     *
     * @return bool
     */
    public function isGeneral()
    {
        return $this->getAttribute('MACType') == self::GENERAL;
    }

    /**
     * Check if type is Billing Disputes
     *
     * @return bool
     */
    public function isBillingDisputes()
    {
        return $this->getAttribute('MACType') == self::BILLING_DISPUTES;
    }

    /**
     * Check if type is Contract Info
     *
     * @return bool
     */
    public function isContractInfo()
    {
        return $this->getAttribute('MACType') == self::CONTRACT_INFO;
    }

    /**
     * Return all the MAC Types
     *
     * @return array
     */
    public static function getOptionsForSelect()
    {
        $options = [];

        foreach (self::select(['MACType', 'MACTypeName'])
                     ->orderBy('MACTypeName')
                     ->get() as $option) {

            $options[$option->getKey()] = $option->getAttribute('MACTypeName');
        }

        return $options;
    }
}
