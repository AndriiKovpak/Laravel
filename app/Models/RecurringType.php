<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecurringType extends Model
{
    /**
     * The integer id for the recurring type.
     *
     * @var int
     */
    private $FrequencyType;

    /**
     * The name of the recurring type
     *
     * @var string
     */
    private $FrequencyName;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'RecurringTypes';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'FrequencyType';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['FrequencyName'];

    /**
     * Return the QueuedReports for this RecurringType
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function QueuedReports()
    {
        return $this->hasMany(\App\Models\QueuedReport::class, 'FrequencyType', 'FrequencyType');
    }
}
