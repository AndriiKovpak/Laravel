<?php

namespace App\Models;

class FTPSetting extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'FTPSettings';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'FTPSettingID';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'UpdatedByUserID', 'Created_at', 'Updated_at'
    ];
}
