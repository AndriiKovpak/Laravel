<?php

namespace App\Models;

class CircuitDescription extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'CircuitDescriptions';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'DescriptionID';

    /**
     * Disable Timestamps
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'Description'
    ];

    /**
     * Return all the Descriptions
     *
     * as ID -> Name
     * @return array
     */
    public static function getOptionsForSelect()
    {
        $options = [];

        foreach (self::select(['DescriptionID', 'Description'])
                     ->orderBy('Description')
                     ->get() as $option) {

            $options[$option->getKey()] = $option->getAttribute('Description');
        }

        return $options;
    }

    /**
     * Return description value
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getAttribute('Description');
    }
}
