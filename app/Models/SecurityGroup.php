<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class SecurityGroup extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'SecurityGroups';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'SecurityGroup';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'SecuityGroup', 'SecurityGroupName', 'IsActive'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'SecuityGroup' =>  'integer',
        'IsActive'     =>  'boolean'
    ];

    /**
     * Return all the Users with this UserStatusType
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function Users()
    {
        return $this->hasMany(\App\Models\User::class, 'SecuityGroup', 'SecuityGroup');
    }

    /**
     * Return the ReportGroups for this SecurityGroup
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ReportGroups()
    {
        return $this->hasMany(\App\Models\ReportGroup::class, 'SecurityGroup', 'SecurityGroup');
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
     * Return the string value
     *
     * @return mixed
     */
    public function __toString()
    {
        return $this->getAttribute('SecuityGroupName');
    }
}