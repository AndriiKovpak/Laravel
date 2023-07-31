<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProcessCode extends Model
{
    /**
     * The integer id for the process code.
     *
     * @var int
     */
    private $ProcessCode;

    /**
     * The name of the process code
     *
     * @var string
     */
    private $ProcessCodeName;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ProcessCodes';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'ProcessCode';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['ProcessCodeName'];

    /**
     * Return the ScannedImages for this ProcessCode
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ScannedImages()
    {
        return $this->hasMany(\App\Models\ScannedImage::class, 'ProcessCode', 'ProcessCode');
    }
}
