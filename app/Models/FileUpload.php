<?php

namespace App\Models;

/**
 * App\Models\FileUpload
 */
class FileUpload extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'FileUploads';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'FileUploadID';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'FileUploadID', 'FileType', 'RecordCount', 'ErrorCount', 'ErrorCode', 'UpdatedByUserID'
    ];

    /**
     * If ErrorCode is longer than 1,000 characters, cut it to 1,000 characters.
     *
     * @param $value
     */
    public function setErrorCodeAttribute($value)
    {
        if (strlen($value) > 1000) {
            $this->attributes['ErrorCode'] = substr($value, 0, 999) . '~';
        } else {
            $this->attributes['ErrorCode'] = $value;
        }
    }
}