<?php

namespace App\Models;
class HandoffType extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'HandoffTypes';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'HandoffID';

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
        'HandoffName'
    ];

    /**
     * Return all the HandoffTypes
     *
     * as ID -> Name
     * @return array
     */
    public static function getOptionsForSelect()
    {
        $options = [];
        foreach (self::select(['HandOffID', 'HandoffName'])
                     ->get() as $option) {
            $options[$option->getAttribute('HandOffID')] = $option->getAttribute('HandoffName');
        }

        return $options;
    }

    /**
     * Return handoff value
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getAttribute('HandoffName');
    }
}
