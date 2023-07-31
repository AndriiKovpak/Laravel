<?php

namespace App\Models;

class StateCode extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'StateCodes';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'State';

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['StateName'];

    /**
     * Do not use timestamps
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Return the Addresses with this state code
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function Addresses()
    {
        return $this->hasMany(\App\Models\Address::class, 'State', 'State');
    }

    /**
     * return array of state codes -> state names
     *
     * @return array
     */
    public static function getStateDropdownOptions()
    {
        $states = [];

        foreach (self::get() as $State) {

            $states[$State->getKey()] = $State->getAttribute('StateName');
        }

        return $states;
    }
}
