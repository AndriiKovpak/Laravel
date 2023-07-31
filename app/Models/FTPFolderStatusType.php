<?php

namespace App\Models;

class FTPFolderStatusType extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'FTPFolderStatusTypes';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'FTPFolderStatus';

    /**
     * Return the FTPFolders for this FTPFolderStatusType
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function FTPFolders()
    {
        return $this->hasMany(FTPFolder::class, 'FTPFolderStatus', 'FTPFolderStatus');
    }
}
