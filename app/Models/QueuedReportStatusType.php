<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QueuedReportStatusType extends Model
{
    /**
     * The integer id for the status type
     *
     * @var int
     */
    private $ReportStatus;

    /**
     * The name of the status type
     *
     * @var string
     */
    private $ReportStatusName;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ReportQueueStatusTypes';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'ReportStatus';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ReportStatusName'
    ];

    /**
     * Return the QueuedReports for this QueuedReportStatusType
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function QueuedReports()
    {
        return $this->hasMany(\App\Models\QueuedReport::class, 'ReportStatus', 'ReportStatus');
    }
}
