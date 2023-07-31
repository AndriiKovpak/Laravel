<?php

namespace App\Models;

class ProcessedMethodType extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ProcessedMethodTypes';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'ProcessedMethod';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['ProcessedMethodName','IsActive'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'IsActive'  =>  'boolean'
    ];

    /**
     * Return the InvoiceAPs for this ProcessedMethodType
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function InvoiceAPs()
    {
        return $this->hasMany(\App\Models\InvoiceAP::class, 'ProcessedMethod', 'ProcessedMethod');
    }

    /**
     * Return all the Processing Method Types for select options
     * as ID -> Name
     * @return array
     */
    public static function getOptionsForSelect()
    {
        $options = [];

        foreach (self::where('IsActive', true)
                    ->orderBy('ProcessedMethodName')
                     ->get() as $option) {

            $options[$option->getKey()] = $option->getAttribute('ProcessedMethodName');
        }

        return $options;
    }

    /**
     * Return Processed Method Name
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getAttribute('ProcessedMethodName');
    }
}
