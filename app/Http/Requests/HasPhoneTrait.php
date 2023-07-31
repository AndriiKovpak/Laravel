<?php
/**
 * Created by PhpStorm.
 * User: bcooper
 * Date: 11/3/2017
 * Time: 9:52 AM
 */

namespace App\Http\Requests;


trait HasPhoneTrait
{
    private function phoneRules($type, $isRequired = true){

        $phoneRegex = "/^(?:(?:\+?1\s*(?:[.-]\s*)?)?(?:\(\s*([2-9]1[02-9]|[2-9][02-8]1|[2-9][02-8][02-9])\s*\)|([2-9]1[02-9]|[2-9][02-8]1|[2-9][02-8][02-9]))\s*(?:[.-]\s*)?)?([2-9]1[02-9]|[2-9][02-9]1|[2-9][02-9]{2})\s*(?:[.-]\s*)?([0-9]{4})?$/";

        return [
            $type => [$isRequired ? 'required' : 'nullable', 'regex:' . $phoneRegex]
        ];
    }

}