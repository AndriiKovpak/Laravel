<?php

namespace App\Http\Requests\Settings\FTPFolders;

use App\Http\Requests\FormRequest;

/**
 * Class EditRequest
 * @package App\Http\Requests\Settings\FTPFolders
 */
class UpdateRequest extends FormRequest
{
    /**
     * Allow this request only for
     * authorized users.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Return validation rules for FTPFolder edit.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'FTPFolderStatus' =>  ['required']
        ];
    }
}