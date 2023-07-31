<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AddressType extends Model
{
    /**
     * The integer id for the address type.
     *
     * @var int
     */
    private $AddressType;

    /**
     * The name of the address type
     *
     * @var string
     */
    private $AddressTypeName;

    /**
     * Is the address type active.
     *
     * @var bool
     */
    private $IsActive;


    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'AddressTypes';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'AddressType';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['AddressTypeName','IsActive'];


    /**
     * Return the Addresses with this type
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function Addresses()
    {
        return $this->hasMany(\App\Models\Address::class, 'AddressType', 'AddressType');
    }
}
