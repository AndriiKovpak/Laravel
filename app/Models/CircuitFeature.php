<?php

namespace App\Models;

class CircuitFeature extends BaseModel
{
    /**
     * The integer id for the feature
     *
     * @var int
     */
    private $CircuitFeatureID;

    /**
     * The id of the circuit to which this feature is tied
     *
     * @var int
     */
    private $CircuitID;

    /**
     * The id of the feature type.
     *
     * @var int
     */
    private $FeatureType;

    /**
     * Feature cost
     *
     * @var float
     */
    private $FeatureCost;

    /**
     * The carrier product code
     *
     * @var string
     */
    private $CarrierProductCode;

    /**
     * The UserID of the person who last updated the feature
     *
     * @var int
     */
    private $UpdatedByUserID;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'CircuitFeatures';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'CircuitFeatureID';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'CircuitID', 'FeatureType', 'FeatureCost', 'CarrierProductCode', 'UpdatedByUserID'
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

    /**
     * Return the FeatureType
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function Feature() // Can't be FeatureType because of the column name
    {
        return $this->belongsTo(\App\Models\FeatureType::class, 'FeatureType', 'FeatureType');
    }

    public function getFeatureCostAttribute($value) {
        return Util::formatCurrency($value);
    }
}
