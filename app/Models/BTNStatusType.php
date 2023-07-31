<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

/**
 * Class BTNStatusType
 * @package App\Models
 */
class BTNStatusType extends BaseModel
{
    const STATUS_ACTIVE     = 1;
    const STATUS_INACTIVE   = 12;
    const STATUS_DELETE     = 2;
    const STATUS_PENDING    = 14;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'BTNStatusTypes';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'BTNStatus';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'BTNStatusName'
    ];

    /**
     * Return the BTNAccounts for this StatusType
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function BTNAccounts()
    {
        return $this->hasMany(\App\Models\BTNAccount::class, 'Status', 'BTNStatus');
    }

    /**
     * Check if the status is active.
     *
     * @return bool
     */
    public function isActive()
    {
        return $this->getAttribute('IsDisplay');
    }

    /**
     * Return only displayable Status Types.
     *
     * TODO: Should this be based on the IsDisplay column or the constants in this file?
     *
     * @param Builder $builder
     * @return Builder
     */
    public function scopeDisplay(Builder $builder)
    {
        return $builder->where('IsDisplay', true);
    }

    /**
     * Return the BTN status name.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getAttribute('BTNStatusName');
    }

    /**
     * Return all the Status Types for select options
     * as ID -> Name
     * @return array
     */
    public static function getOptionsForSelect()
    {
        $options = [];

        foreach (self::select(['BTNStatus', 'BTNStatusName'])
                     ->display()
                     ->get() as $option) {

            $options[$option->getKey()] = $option->getAttribute('BTNStatusName');
        }

        return $options;
    }
}
