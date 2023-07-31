<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    /**
     * The integer id for the report.
     *
     * @var int
     */
    private $ReportID;

    /**
     * The name of the report
     *
     * @var string
     */
    private $ReportName;

    /**
     * Description of the report
     *
     * @var string
     */
    private $ReportDescription;

    /**
     * The UserID of the person who last updated the report
     *
     * @var int
     */
    private $UpdatedByUserID;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'Reports';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'ReportID';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ReportName', 'ReportDescription', 'UpdatedByUserID'
    ];

    /**
     * Return the QueuedReports for this Report type
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function QueuedReports()
    {
        return $this->hasMany(\App\Models\QueuedReport::class, 'ReportID', 'ReportID');
    }

    /**
     * Return the ReportGroups for this Report type
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function Groups()
    {
        return $this->belongsToMany(\App\Models\ReportGroup::class,
            'Reports_ReportGroups',
            'ReportID', 'ReportGroupID');
    }
}
