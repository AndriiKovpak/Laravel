<?php

namespace App\Models;

class CircuitVoice extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'CircuitsVoice';

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
        'CircuitID', 'DescriptionID', 'Email', 'ILEC_ID1', 'ILEC_ID2', 'ServiceAddressID', 'LocationAAddressID',
        'LocationZAddressID', 'SPID_Phone1', 'SPID_Phone2', 'PointToNumber',
        'LD_PIC', 'UpdatedByUserID'
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
}
