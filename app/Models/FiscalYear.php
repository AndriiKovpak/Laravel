<?php

namespace App\Models;

class FiscalYear extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'FiscalYears';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'FiscalYearID';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'FiscalYearName', 'BeginDate', 'EndDate'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'BeginDate' =>  'datetime',
        'EndDate'   =>  'datetime'
    ];

    /**
     * Do not use timestamps in here.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Return the ScannedImages for this FiscalYear
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ScannedImages()
    {
        return $this->hasMany(\App\Models\ScannedImage::class, 'FiscalYearID', 'FiscalYearID');
    }

    /**
     * Return all the Fiscal Years for select options
     * as ID -> Name
     * @return array
     */
    public static function getOptionsForSelect()
    {
        $options = [];

        foreach (self::select(['FiscalYearID', 'FiscalYearName'])

                     ->get() as $option) {
            if($option->getKey() != 0){
                $options[$option->getKey()] = $option->getAttribute('FiscalYearName');
            }

        }
        $SortBy[0] = 'Sort by Fiscal Year';
        return  $SortBy + array_reverse($options, true);
    }
}
