<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class Category extends BaseModel
{
    const VOICE         =   1;
    const DATA          =   3;
    const SATELLITE     =   4;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'Categories';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'CategoryID';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'CategoryName', 'IsActive'
    ];

    /**
     * Return the Circuits for this Category
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function Circuits()
    {
        return $this->hasMany(\App\Models\Circuit::class, 'Category', 'CategoryID');
    }

    /**
     * Return the ServiceTypes for this Category
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ServiceTypes()
    {
        return $this->hasMany(\App\Models\ServiceType::class, 'Category', 'CategoryID');
    }

    /**
     * Return the FeatureTypes for this ServiceType
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function FeatureTypes()
    {
        return $this->hasMany(\App\Models\FeatureType::class, 'CategoryID', 'CategoryID');
    }

    /**
     * Check if the Category is Voice
     *
     * @return bool
     */
    public function isVoice()
    {
        return ($this->getKey() == self::VOICE);
    }

    /**
     * Check if the Category is Satellite
     *
     * @return bool
     */
    public function isSatellite()
    {
        return ($this->getKey() == self::SATELLITE);
    }

    /**
     * Check if the Category is Data
     *
     * @return bool
     */
    public function isData()
    {
        return ($this->getKey() == self::DATA);
    }

    /**
     * Return only active Security Groups
     *
     * @param Builder $builder
     * @return Builder
     */
    public function scopeActive(Builder $builder)
    {
        return $builder->where('IsActive', true);
    }

    /**
     * Return all the Carriers for select options
     * as ID -> Name
     * @return array
     */
    public static function getOptionsForSelect()
    {
        $options = [];

        foreach (self::select(['CategoryID', 'CategoryName'])
                     ->active()
                     ->get() as $option) {

            $options[$option->getKey()] = $option->getAttribute('CategoryName');
        }

        return $options;
    }

    /**
     * Return the Category Name
     *
     * @return mixed
     */
    public function __toString()
    {
        return $this->getAttribute('CategoryName');
    }
}
