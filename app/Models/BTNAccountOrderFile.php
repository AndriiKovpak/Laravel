<?php

namespace App\Models;

class BTNAccountOrderFile extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'BTNAccountOrderFiles';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'OrderFileID';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'BTNAccountOrderID', 'FilePath', 'UpdatedByUserID' ,'OriginalName'
    ];

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
     * Return the BTNAccountOrder
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function BTNAccountOrder()
    {
        return $this->belongsTo(\App\Models\BTNAccountOrder::class, 'BTNAccountOrderID', 'BTNAccountOrderID');
    }

    /**
     * Return full path for a document
     *
     * @return string
     */
    public function getFullPath()
    {
        return storage_path($this->getAttribute('FilePath'));
    }

    /**
     * Return extension for a document
     *
     * @return string
     */
    public function getFileExtension()
    {
        return pathinfo($this->getFullPath())['extension'];
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
