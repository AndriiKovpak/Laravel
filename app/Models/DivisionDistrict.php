<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class DivisionDistrict extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'DivisionDistricts';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'DivisionDistrictID';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'DivisionDistrictCode', 'DivisionDistrictName', 'IsActive', 'UpdatedByUserID'
    ];

    /**
     * Return the BTNAccounts for this DivisionDistrict
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function BTNAccounts()
    {
        return $this->hasMany(\App\Models\BTNAccount::class, 'DivisionDistrictID', 'DivisionDistrictID');
    }

    /**
     * Return the Circuits for this DivisionDistrict
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function Circuits()
    {
        return $this->hasMany(\App\Models\Circuit::class, 'DivisionDistrictID', 'DivisionDistrictID');
    }

    /**
     * Return the Users for this DivisionDistrict
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function Users()
    {
        return $this->belongsToMany(\App\Models\User::class,
            'Users_DivisionDistricts',
            'DivisionDistrictID', 'UserID');
    }

    /**
     * Return the Division District Name
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getAttribute('DivisionDistrictName');
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
     * Return all the Status Types for select options
     * as ID -> Name
     * @return array
     */
    public static function getOptionsForSelect()
    {
        $options = [];

        foreach (self::select(['DivisionDistrictID', 'DivisionDistrictName'])
                     ->active()
                     ->orderBy('DivisionDistrictName')
                     ->get() as $option) {

            $options[$option->getKey()] = $option->getAttribute('DivisionDistrictName');
        }

        return $options;
    }
}
