<?php
/**
 * Created by PhpStorm.
 * User: bcooper
 * Date: 3/15/2017
 * Time: 2:40 PM
 */

namespace App\Models;


class CircuitUpdateQueue extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'CircuitUpdateQueue';

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
        'CircuitID','CreateDT', 'ChangeType', 'ReportDT',
        'UpdatedByUserID', 'Created_at', 'Updated_at'
    ];

    /**
     * Return the BTNAccount
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function Circuit()
    {
        return $this->belongsTo(\App\Models\Circuit::class, 'CircuitID', 'CircuitID');
    }

}
