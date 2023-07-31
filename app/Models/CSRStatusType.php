<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CSRStatusType extends Model
{
    /**
     * The integer id for the status.
     *
     * @var int
     */
    private $CSRStatus;

    /**
     * The name of the status
     *
     * @var string
     */
    private $CSRStatusName;


    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'CSRStatusTypes';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'CSRStatus';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'CSRStatusName'
    ];
}
