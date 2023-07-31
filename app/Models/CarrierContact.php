<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarrierContact extends Model
{
    /**
     * The integer id for the contact.
     *
     * @var int
     */
    private $CarrierContactID;

    /**
     * The id of the Carrier
     *
     * @var int
     */
    private $CarrierID;

    /**
     * Name of the contact.
     *
     * @var string
     */
    private $Name;

    /**
     * Title of the contact
     *
     * @var string
     */
    private $Title;

    /**
     * The contact's phone number
     *
     * @var string
     */
    private $PhoneNumber;

    /**
     * The contact's phone extension
     *
     * @var string
     */
    private $PhoneExt;

    /**
     * The contact's mobile phone number
     *
     * @var string
     */
    private $MobilePhoneNumber;


    /**
     * The contact's office phone number
     *
     * @var string
     */
    private $OfficePhoneNumber;

    /**
     * The contact's email address
     *
     * @var string
     */
    private $EmailAddress;

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
    protected $table = 'CarrierContacts';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'CarrierContactID';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'CarrierID', 'Name', 'Title', 'PhoneNumber', 'PhoneExt',
        'MobilePhoneNumber', 'OfficePhoneNumber', 'EmailAddress', 'UpdatedByUserID'
    ];

    /**
     * Return the Carrier
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function Carrier()
    {
        return $this->belongsTo(\App\Models\Carrier::class, 'CarrierID', 'CarrierID');
    }
}
