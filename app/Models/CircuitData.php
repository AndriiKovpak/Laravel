<?php

namespace App\Models;

class CircuitData extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'CircuitsData';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'CircuitID';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'CircuitID', 'DescriptionID', 'Email', 'ILEC_ID1', 'ILEC_ID2', 'ServiceAddressID', 'LocationAAddressID', 'HandoffID',
        'LocationZAddressID', 'QoS_CIR', 'PortSpeed', 'Mileage', 'NetworkIPAddress', 'UpdatedByUserID', 'Dmarc'
    ];

    /**
     * Return the Description
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function Description()
    {
        return $this->belongsTo(\App\Models\CircuitDescription::class, 'DescriptionID', 'DescriptionID');
    }

    /**
     * Return the ServiceAddress
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ServiceAddress()
    {
        return $this->belongsTo(\App\Models\Address::class, 'ServiceAddressID', 'AddressID');
    }

    /**
     * Return the LocationAAddress
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function LocationAAddress()
    {
        return $this->belongsTo(\App\Models\Address::class, 'LocationAAddressID', 'AddressID');
    }

    /**
     * Return the LocationZAddress
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function LocationZAddress()
    {
        return $this->belongsTo(\App\Models\Address::class, 'LocationZAddressID', 'AddressID');
    }

    /**
     * Return the Handoff
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function Handoff()
    {
        return $this->belongsTo(\App\Models\HandoffType::class, 'HandoffID', 'HandoffID');
    }
}
