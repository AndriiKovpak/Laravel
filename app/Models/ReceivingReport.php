<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReceivingReport extends Model
{
    /**
     * The integer id for the receiving report.
     *
     * @var int
     */
    private $ReceivingReportID;

    /**
     * Date the report was created
     *
     * @var string
     */
    private $CreateDate;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ReceivingReports';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'ReceivingReportID';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'CreateDate'
    ];

    /**
     * Return the InvoiceAPs for this receiving report
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function InvoiceAPs()
    {
        return $this->belongsToMany(\App\Models\InvoiceAP::class,
            'InvoiceAccountsPayable_ReceivingReports',
            'ReceivingReportID', 'InvoiceAPID');
    }
}
