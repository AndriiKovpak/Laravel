<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class FeatureType extends Model
{
    /**
     * The integer id for the feature type.
     *
     * @var int
     */
    private $FeatureType;

    /**
     * The name of the feature type
     *
     * @var string
     */
    private $FeatureName;

    /**
     * Integer id for the category
     *
     * @var int
     */
    private $CategoryID;

    /**
     * Code assigned to the feature type
     *
     * @var string
     */
    private $FeatureCode;

    /**
     * Is the feature type active?
     *
     * @var bool
     */
    private $IsActive;

    /**
     * The UserID of the person who last updated the feature type
     *
     * @var int
     */
    private $UpdatedByUserID;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'FeatureTypes';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'FeatureType';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'FeatureName', 'CategoryID', 'FeatureCode', 'IsActive', 'UpdatedByUserID'
    ];

    /**
     * Return the CircuitFeatures for this FeatureType
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function Features()
    {
        return $this->hasMany(\App\Models\CircuitFeature::class, 'FeatureType', 'FeatureType');
    }

    /**
     * Return the Category
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function Category()
    {
        return $this->belongsTo(\App\Models\Category::class, 'CategoryID', 'CategoryID');
    }

    /**
     * Return only active Feature Types
     *
     * @param Builder $builder
     * @return Builder
     */
    public function scopeActive(Builder $builder)
    {
        return $builder->where('IsActive', true);
    }

    /**
     * Return all the Feature Types for select options
     * as FeatureType => FeatureName
     * @return array
     */
    public static function getOptionsForSelect($CategoryID = false)
    {
        $options = [];

        $query = self::select(['FeatureType', 'FeatureName'])
            ->active()
            ->orderBy('FeatureName');

        if ($CategoryID !== false) {
            $query->where('CategoryID', $CategoryID);
        }

        foreach ($query->get() as $option) {

            $options[$option->getKey()] = $option->getAttribute('FeatureName');
        }

        return $options;
    }

    /**
     * Return all the Feature Types for select options
     * as FeatureType => ['FeatureName' => FeatureName, 'CategoryID' => CategoryID]
     * @return array
     */
    public static function getOptionsForDynamicSelect($CategoryID = false)
    {
        $options = [];

        $query = self::select(['FeatureType', 'FeatureName', 'CategoryID'])
            ->active()
            ->orderBy('FeatureName');

        foreach ($query->get() as $option) {
            $options[$option->getKey()] = [
                'FeatureName' => $option->getAttribute('FeatureName'),
                'CategoryID' => $option->getAttribute('CategoryID'),
                'show' => ($CategoryID === false || $CategoryID == $option->getAttribute('CategoryID')),
            ];
        }

        return $options;
    }
}
