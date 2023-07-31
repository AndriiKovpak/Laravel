<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProcessedType extends Model
{
    /**
     * The integer id for the processed type.
     *
     * @var int
     */
    private $ProcessedType;

    /**
     * The name of the processed type
     *
     * @var string
     */
    private $ProcessedTypeName;

    /**
     * Is the processed type active.
     *
     * @var bool
     */
    private $IsActive;


    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ProcessedTypes';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'ProcessedType';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['ProcessedTypeName','IsActive'];

    /**
     * Return the ScannedImages for this ProcessedType
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ScannedImages()
    {
        return $this->hasMany(\App\Models\ScannedImage::class, 'ProcessedType', 'ProcessedType');
    }
}
