<?php

namespace App\Models;

class FTPFolder extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'FTPFolders';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'FTPFolderID';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'FTPFolderStatus', 'UpdatedByUserID', 'Created_at', 'Updated_at'
    ];

    /**
     * Return the FTPFolderStatusType for this FTPFolder
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function FTPFolderStatusType()
    {
        return $this->hasOne(FTPFolderStatusType::class, 'FTPFolderStatus', 'FTPFolderStatus');
    }

    public function scopeScheduled($query)
    {
        return $query->where('FTPFolderStatus', '2');
    }

    public function scopePending($query)
    {
        return $query->where('FTPFolderStatus', '2') // Scheduled
            ->orWhere('FTPFolderStatus', '3'); // Processing
    }
}
