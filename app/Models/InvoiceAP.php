<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Builder;

class InvoiceAP extends BaseModel
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'InvoicesAccountsPayable';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'InvoiceAPID';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'BTNAccountID', 'InvoiceNum', 'ProcessedMethod', 'ScannedImageID', 'BillDate', 'DueDate', 'ServiceFromDate',
        'ServiceToDate', 'IsFinalBill', 'CurrentChargeAmount', 'PastDueAmount', 'CreditAmount', 'RemittanceAddressID',
        'TotalPaidAmount', 'CheckSentDate', 'CheckNumber', 'IsResearch', 'Note', 'UpdatedByUserID'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'BillDate'          =>  'datetime',
        'DueDate'           =>  'datetime',
        'ServiceFromDate'   =>  'datetime',
        'ServiceToDate'     =>  'datetime',
        'CheckSentDate'     =>  'datetime',
        'IsFinalBill'           =>  'boolean',
        'IsResearch'            =>  'boolean',
        'UpdatedByUserID'       =>  'integer'
    ];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    /*
    public static function boot()
    {
        parent::boot();

        static::addGlobalScope('Status', function (Builder $builder) {
            $builder->where('Status', 1);
        });
    }
    */

    /**
     * Return the ReceivingReports for this invoice
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function ReceivingReports()
    {
        return $this->belongsToMany(\App\Models\ReceivingReport::class,
            'InvoiceAccountsPayable_ReceivingReports',
            'InvoiceAPID', 'ReceivingReportID');
    }

    /**
     * Return the BTNAccount
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function BTNAccount()
    {
        return $this->belongsTo(\App\Models\BTNAccount::class, 'BTNAccountID', 'BTNAccountID');
    }

    /**
     * Return the ProcessedMethod
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ProcessedMethodType()
    {
        return $this->belongsTo(\App\Models\ProcessedMethodType::class, 'ProcessedMethod', 'ProcessedMethod');
    }

    /**
     * Return the ScannedImage
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function ScannedImage()
    {
        return $this->hasOne(\App\Models\ScannedImage::class, 'ScannedImageID', 'ScannedImageID');
    }

    /**
     * Return the RemittanceAddress
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function RemittanceAddress()
    {
        return $this->belongsTo(\App\Models\InvoiceRemittanceAddress::class, 'RemittanceAddressID', 'RemittanceAddressID');
    }

    /**
     * Return the users that updated an InvoiceAP
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function UpdatedByUser()
    {
        return $this->hasOne(\App\Models\User::class, 'UserID', 'UpdatedByUserID');
    }

    public function getCurrentChargeAmountAttribute($value) {
        return Util::formatCurrency($value);
    }
    public function getPastDueAmountAttribute($value) {
        return Util::formatCurrency($value);
    }
    public function getCreditAmountAttribute($value) {
        return Util::formatCurrency($value);
    }
    public function getTotalPaidAmountAttribute($value) {
        return Util::formatCurrency($value);
    }
}
