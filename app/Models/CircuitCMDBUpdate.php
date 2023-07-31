<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CircuitCMDBUpdate extends Model
{
    /**
     * The integer id for the update.
     *
     * @var int
     */
    private $CircuitUpdateQueueID;

    /**
     * The id of the circuit
     *
     * @var int
     */
    private $CircuitID;

    /**
     * Date the update was created.
     *
     * @var string
     */
    private $CreateDate;

    /**
     * Type of change
     *
     * @var string
     */
    private $ChangeType;

    /**
     * Report date
     *
     * @var string
     */
    private $ReportDate;


    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'CircuitCMDBUpdateQueue';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'CircuitUpdateQueueID';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'CircuitID', 'CreateDate', 'ChangeType', 'ReportDate'
    ];

    /**
     * Return the Circuit
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function Circuit()
    {
        return $this->belongsTo(\App\Models\Circuit::class, 'CircuitID', 'CircuitID');
    }
}
