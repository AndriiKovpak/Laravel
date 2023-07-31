<?php

namespace App\Models;

class BTNAccountCSRFile extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'BTNAccountCSRFiles';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'CSRFileID';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'BTNAccountCSRID', 'FilePath', 'UpdatedByUserID'
    ];

    /**
     * Return the BTNAccountCSR
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function BTNAccountCSR()
    {
        return $this->belongsTo(\App\Models\BTNAccountCSR::class, 'BTNAccountCSRID', 'BTNAccountCSRID');
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
