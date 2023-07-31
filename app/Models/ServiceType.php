<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class ServiceType extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ServiceTypes';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'ServiceType';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['ServiceTypeName', 'Category', 'IsActive'];

    /**
     * Disable insert and updated of created_at and updated_at columns.
     * @var bool
     */
    public $timestamps = false;

    /**
     * Return the Circuits for this ServiceType
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function Circuits()
    {
        return $this->hasMany(\App\Models\Circuit::class, 'ServiceType', 'ServiceType');
    }

    /**
     * Return the Category
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function Category()
    {
        return $this->belongsTo(\App\Models\Category::class, 'CategoryID', 'Category');
    }

    /**
     * Return the Category
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function CategoryName($id)
    {
        return Category::find($id);
    }

    /**
     * Return Service Type Name
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getAttribute('ServiceTypeName');
    }

    /**
     * Return only active Security Groups
     *
     * @param Builder $builder
     * @return Builder
     */
    public function scopeActive(Builder $builder)
    {
        return $builder->where('IsActive', true);
    }

    /**
     * Return all the Status Types for select options
     * as ID -> Name
     * @return array
     */
    public static function getOptionsForSelect($CategoryID = false)
    {
        $options = [];

        $query = self::select(['ServiceType', 'ServiceTypeName'])
            ->active()
            ->orderBy('ServiceTypeName');

        if ($CategoryID !== false) {
            $query->where('Category', $CategoryID);
        }

        foreach ($query->get() as $option) {

            $options[$option->getKey()] = $option->getAttribute('ServiceTypeName');
        }

        return $options;
    }
}
