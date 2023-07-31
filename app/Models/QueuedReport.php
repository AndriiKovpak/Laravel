<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QueuedReport extends Model
{
    /**
     * The integer id for the queued report.
     *
     * @var int
     */
    private $ReportQueueID;

    /**
     * The integer id for the type of report queued.
     *
     * @var int
     */
    private $ReportID;

    /**
     * Integer id for the user generating the report.
     *
     * @var int
     */
    private $UserID;

    /**
     * Date the report is queued to run next.
     *
     * @var string
     */
    private $NextReportDate;

    /**
     * Integer id representing the frequency at which the report is run.
     *
     * @var int
     */
    private $ReportFrequencyType;

    /**
     * What character should be used for quotes in the exported file.
     *
     * @var string
     */
    private $QuoteChar;

    /**
     * What character should be used as a delimiter in the exported file.
     *
     * @var string
     */
    private $DelimChar;

    /**
     * Should field names be included in the first row?
     *
     * @var bool
     */
    private $IsFieldNames;

    /**
     * Integer id representing the status of the queued report.
     *
     * @var int
     */
    private $ReportStatus;

    /**
     * Custom subject to be used in the email generated
     *
     * @var string
     */
    private $CustomSubject;

    /**
     * Custom message to be used in the email generated
     *
     * @var string
     */
    private $CustomMessage;

    /**
     * Custom filename to be used for the exported file
     *
     * @var string
     */
    private $CustomFilename;

    /**
     * Path to the PGP Key (if any)
     *
     * @var string
     */
    private $PGPKeyPath;

    /**
     * The UserID of the person who last updated the queued report
     *
     * @var int
     */
    private $UpdatedByUserID;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ReportQueue';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'ReportQueueID';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ReportID', 'UserID', 'NextReportDate', 'ReportFrequencyType', 'QuoteChar', 'DelimChar', 'IsFieldNames',
        'ReportStatus', 'CustomSubject', 'CustomMessage', 'CustomFilename', 'PGPKeyPath', 'UpdatedByUserID'
    ];

    public function scopeActive($query)
    {
        return $query->where('ReportStatus', '1');
    }

    public function scopeDue($query)
    {
        return $query->where('ReportStatus', '1')
            ->where('NextReportDate', '<=', date('Y-m-d H:i:s'));
    }

    /**
     * Return the Report
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function Report()
    {
        return $this->belongsTo(\App\Models\Report::class, 'ReportID', 'ReportID');
    }

    /**
     * Return the RecurringType
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function RecurringType()
    {
        return $this->belongsTo(\App\Models\RecurringType::class, 'ReportFrequencyType', 'RecurringType');
    }

    /**
     * Return the QueuedReportStatusType
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function StatusType()
    {
        return $this->belongsTo(\App\Models\QueuedReportStatusType::class, 'ReportStatus', 'ReportStatus');
    }

    /**
     * Return the QueuedReportRecipients for this QueuedReport
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function Recipients()
    {
        return $this->hasMany(\App\Models\QueuedReportRecipient::class, 'ReportQueueID', 'ReportQueueID');
    }
}
