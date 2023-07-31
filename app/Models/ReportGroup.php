<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportGroup extends Model
{
    /**
     * The integer id for the report group.
     *
     * @var int
     */
    private $ReportGroupID;

    /**
     * The name of the report group
     *
     * @var string
     */
    private $ReportGroupName;

    /**
     * Integer id representing the securty group
     *
     * @var int
     */
    private $SecurityGroup;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ReportGroups';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'ReportGroupID';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ReportGroupName', 'SecurityGroup'
    ];

    /**
     * Return the SecurityGroup
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function SecurityGroup()
    {
        return $this->belongsTo(\App\Models\SecurityGroup::class, 'SecurityGroup', 'SecurityGroup');
    }

    /**
     * Return the Report types for this Report group
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function Reports()
    {
        return $this->belongsToMany(\App\Models\Report::class,
            'Reports_ReportGroups',
            'ReportGroupID', 'ReportID');
    }
}
