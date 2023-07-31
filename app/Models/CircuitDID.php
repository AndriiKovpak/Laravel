<?php

namespace App\Models;

class CircuitDID extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'CircuitDIDs';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'CircuitDIDID';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'CircuitID', 'DID', 'DIDNote', 'UpdatedByUserID'
    ];

    /**
     * Return the Circuit
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function Circuit()
    {
        return $this->belongsTo(\App\Models\Circuit::class, 'CircuitID', 'CircuitID');
    }
}
