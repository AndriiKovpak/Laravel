<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarrierNote extends Model
{
    /**
     * The integer id for the note.
     *
     * @var int
     */
    private $CarrierNoteID;

    /**
     * The id of the Carrier
     *
     * @var int
     */
    private $CarrierID;

    /**
     * The saved note
     *
     * @var string
     */
    private $Note;

    /**
     * The UserID of the person who last updated the contact
     *
     * @var int
     */
    private $UpdatedByUserID;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'CarrierNotes';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'CarrierNoteID';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'CarrierID', 'Note','PhoneExt','UpdatedByUserID'
    ];
}
