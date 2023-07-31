<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class Address extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'Addresses';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'AddressID';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'SiteName', 'Address1', 'Address2', 'City',
        'State', 'Zip', 'AddressType', 'UpdatedByUserID'
    ];

    protected $appends = ['Search'];

    /**
     * Return the StateCode
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function StateCode()
    {
        return $this->belongsTo(\App\Models\StateCode::class, 'State', 'State');
    }

    /**
     * Return the AddressType
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function AddressType()
    {
        return $this->belongsTo(\App\Models\AddressType::class, 'AddressType', 'AddressType');
    }

    public function getSearchAttribute() {
        return $this->__toString();
    }

    /**
     * Return the address line.
     *
     * @return string
     */
    public function __toString()
    {
        $data  = [];
        $parts = ['Address1', 'Address2', 'City', 'State', 'Zip'];

        foreach ($parts as $part) {

            if (! empty($this->getAttribute($part))) {

                $data[] = $this->getAttribute($part);
            }
        }

        return join(', ', $data);
    }

    /**
     * Search an Address
     *
     * @param Builder $builder
     * @param $query
     * @return $this
     */
    public function scopeSearch(Builder $builder, $query)
    {
        $sql = 'LTRIM(RTRIM(CONCAT(Address1, \' \', Address2, \' \', City, \' \', State, \' \', Zip))) LIKE ?';

        return $builder->whereRaw($sql, ['%' . $query . '%']);
    }

    /**
     * Return Name
     *
     * @return string|null
     */
    public function getName()
    {
        return $this->getAttribute('SiteName');
    }
}
