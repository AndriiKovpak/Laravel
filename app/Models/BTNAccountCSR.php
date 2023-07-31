<?php

namespace App\Models;

class BTNAccountCSR extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'BTNAccountCSRs';

    /**
     * Date inputs
     *
     * @var array
     */
    protected $dates = ['PrintedDate'];

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'BTNAccountCSRID';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'BTNAccountID', 'AccountNum', 'FilePath', 'PrintedDate', 'UpdatedByUserID'
    ];

    /**
     * Cast some attributes
     *
     * @var array
     */
    protected $casts = [
        'PrintedDate'   =>  'datetime'
    ];

    /**
     * Return the BTNAccountCSRFiles for this BTNAccountCSR
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function Files()
    {
        return $this->hasMany(\App\Models\BTNAccountCSRFile::class, 'BTNAccountCSRID', 'BTNAccountCSRID');
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
     * Return full path for a document
     *
     * @return string
     */
    public function getFullPath()
    {
        return storage_path($this->getAttribute('FilePath'));
    }

    public function getPath()
    {

        return $this->getAttribute('FilePath');
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
        return $this->getAttribute('FilePath');
    }
}
