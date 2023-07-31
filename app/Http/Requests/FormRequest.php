<?php

namespace App\Http\Requests;

use Carbon\Carbon;

/**
 * Class FormRequest
 * @package App\Http\Requests
 */
abstract class FormRequest extends \Illuminate\Foundation\Http\FormRequest
{
    /**
     * List of date columns
     * to be converted into Carbon
     *
     * @var array
     */
    protected $dates = [];

    /**
     * Return only validated data.
     *
     * @param boolean $withoutEmptyValues
     * @return array
     */
    public function data($withoutEmptyValues = true)
    {
        $data = $this->only(array_keys($this->rules()));
        if (($withoutEmptyValues == true)) {

            $data = array_filter($data);
        }

        foreach ($this->dates as $column) {

            if (!empty($data[$column])) {
                $data[$column] = Carbon::parse($data[$column]);
            } else {
                $data[$column] = null;
            }
        }

        return $data;
    }


}