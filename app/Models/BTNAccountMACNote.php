<?php

namespace App\Models;

class BTNAccountMACNote extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'BTNAccountMACNotes';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'BTNMACNoteID';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'BTNMACID', 'Note', 'UpdatedByUserID'
    ];

    /**
     * Return the CircuitMAC
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function BTNAccountMAC()
    {
        return $this->belongsTo(\App\Models\BTNAccountMAC::class, 'BTNMACID', 'BTNMACID');
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
