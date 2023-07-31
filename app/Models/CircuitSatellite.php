<?php

namespace App\Models;

class CircuitSatellite extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'CircuitsSatellite';

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
        'CircuitID', 'DeviceType', 'DeviceMake', 'DeviceModel', 'IMEI', 'SIM', 'AssignedToName', 'UpdatedByUserID'
    ];
}
