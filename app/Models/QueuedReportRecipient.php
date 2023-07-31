<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QueuedReportRecipient extends Model
{
    /**
     * The integer id for the recipient.
     *
     * @var int
     */
    private $DistributionListID;

    /**
     * Integer id for the queued report
     *
     * @var int
     */
    private $ReportQueueID;

    /**
     * Email address to which the report should be sent
     *
     * @var string
     */
    private $EmailAddress;

    /**
     * The UserID of the person who last updated the record
     *
     * @var int
     */
    private $UpdatedByUserID;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ReportQueueDistributionList';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'DistributionListID';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ReportQueueID', 'EmailAddress', 'UpdatedByUserID'
    ];

    /**
     * Return the QueuedReport
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function QueuedReport()
    {
        return $this->belongsTo(\App\Models\QueuedReport::class, 'ReportQueueID', 'ReportQueueID');
    }
}
