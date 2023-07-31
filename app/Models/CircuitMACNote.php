<?php

namespace App\Models;

class CircuitMACNote extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'CircuitMACNotes';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'CircuitMACNoteID';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'CircuitMACID', 'Note', 'UpdatedByUserID'
    ];

    /**
     * Return the CircuitMAC
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function CircuitMAC()
    {
        return $this->belongsTo(\App\Models\CircuitMAC::class, 'CircuitMACID', 'CircuitMACID');
    }

    /**
     * Return the Note
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getAttribute('Note');
    }
}
