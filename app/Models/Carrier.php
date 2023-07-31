<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class Carrier extends BaseModel
{
    // use SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'Carriers';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'CarrierID';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'CarrierName', 'VendorCode', 'CarrierPhoneNum', 'CarrierSupportPhoneNum',
        'CarrierAddressID', 'UpdatedByUserID', 'CarrierURL', 'CarrierUserName', 'CarrierPassword',
        'InvoiceAvailableDay', 'IsPaperless', 'IsActive'
    ];

    /**
     * Return the BTNAccounts for this Carrier
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function BTNAccounts()
    {
        return $this->hasMany(\App\Models\BTNAccount::class, 'CarrierID', 'CarrierID');
    }

    /**
     * Return the CarrierContacts for this Carrier
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function Contacts()
    {
        return $this->hasMany(\App\Models\CarrierContact::class, 'CarrierID', 'CarrierID');
    }

    /**
     * Return the CarrierContacts for this Carrier
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function Notes()
    {
        return $this->hasMany(\App\Models\CarrierNote::class, 'CarrierID', 'CarrierID');
    }

    /**
     * Return the Address
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function Address()
    {
        return $this->belongsTo(\App\Models\Address::class, 'CarrierAddressID', 'AddressID');
    }

    /**
     * Return the Carrier Name
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getAttribute('CarrierName');
    }

    /**
     * Return only Carriers with non-empty names
     *
     * @param Builder $builder
     * @return Builder
     */
    public function scopeNotEmpty(Builder $builder)
    {
        return $builder->whereRaw('LEN(CarrierName) > 0');
    }

    /**
     * Return all the Carriers for select options
     * as ID -> Name
     * @return array
     */
    public static function getOptionsForSelect()
    {
        $options = [];

        foreach (self::select(['CarrierID', 'CarrierName'])
            ->notEmpty()
            ->where('IsActive', 1)
            ->orderBy('CarrierName')
            ->get() as $option) {

            $options[$option->getKey()] = $option->getAttribute('CarrierName');
        }

        return $options;
    }
}
