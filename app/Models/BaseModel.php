<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class BaseModel
 * @package App\Models
 */
class BaseModel extends Model
{
    /**
     * The name of the "created at" column.
     *
     * @var string
     */
    const CREATED_AT = 'Created_at';

    /**
     * The name of the "updated at" column.
     *
     * @var string
     */
    const UPDATED_AT = 'Updated_at';

    /**
     * Get the date format for MS SQL server
     *
     * @return string
     */
    public function getDateFormat()
    {
        return 'Y-m-d H:i:s.u';
    }

    /**
     * Format datetime value fro MS SQL's format.
     *
     * @param \DateTime|int $value
     * @return string
     */
    public function fromDateTime($value)
    {
        return substr(parent::fromDateTime($value), 0, -3);
    }

    /**
     *
     */
    // public static function boot()
    // {
    //     static::saving(function ($model) {

    //         if (auth()->check() && in_array('UpdatedByUserID', $model->getFillable())) {

    //             $model->setAttribute('UpdatedByUserID', auth()->user());
    //         }
    //     });
    // }
}
