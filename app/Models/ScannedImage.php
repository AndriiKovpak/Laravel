<?php

namespace App\Models;

class ScannedImage extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ScannedImages';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'ScannedImageID';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'BTNAccountID', 'BillDate', 'ImagePath', 'ProcessedType', 'ProcessCode', 'BatchDate', 'SubAccountBTNCircuitID',
        'FiscalYearID', 'IsArchived', 'IsImage', 'IsReceivingRptImage', 'FTPImagePath', 'IsSentToMSO', 'UpdatedByUserID'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'BillDate'      =>  'datetime',
        'BatchDate'     =>  'datetime'
    ];

    /**
     * Return the InvoiceAP
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function InvoiceAP()
    {
        return $this->hasOne(\App\Models\InvoiceAP::class, 'ScannedImageID', 'ScannedImageID');
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
     * Return the ProcessedType
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ProcessedType()
    {
        return $this->belongsTo(\App\Models\ProcessedType::class, 'ProcessedType', 'ProcessedType');
    }

    /**
     * Return the ProcessCode
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ProcessCode()
    {
        return $this->belongsTo(\App\Models\ProcessCode::class, 'ProcessCode', 'ProcessCode');
    }

    /**
     * Return the FiscalYear
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function FiscalYear()
    {
        return $this->belongsTo(\App\Models\FiscalYear::class, 'FiscalYearID', 'FiscalYearID');
    }

    /**
     * Return full path for this image
     *
     * @return string
     */
    public function getFullPath()
    {
        return storage_path(str_replace("storage\\", "", $this->getAttribute('ImagePath')));
    }

    /**
     * Check if file exists
     *
     * @return boolean
     */
    public function documentExists()
    {
        return app('files')->exists($this->getFullPath());
    }
}
