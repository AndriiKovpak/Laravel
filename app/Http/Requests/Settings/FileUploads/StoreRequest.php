<?php

namespace App\Http\Requests\Settings\FileUploads;

use App\Http\Requests\FormRequest;
use App\Models\FileUpload;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\UploadedFile;

/**
 * Class StoreRequest
 *
 * @package App\Http\Requests\Settings\FileUploads
 */
class StoreRequest extends FormRequest
{
    /**
     * Authorize this request
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        // Only log file upload errors, excluding UPLOAD_ERR_INI_SIZE.
        if (
            $this->file('file-upload') instanceof UploadedFile &&
            !$this->file('file-upload')->isValid() &&
            $this->file('file-upload')->getError() != UPLOAD_ERR_INI_SIZE
        ) {
            FileUpload::create([
                'FileType'    => $this->input('file-type'),
                'RecordCount' => 0,
                'ErrorCount'  => 0,
                'ErrorCode'   => $this->file('file-upload')->getErrorMessage(),
            ]);
        }

        parent::failedValidation($validator);
    }

    /**
     * Get custom rules for validation.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'file-type' => 'required|in:New Circuits,Update Circuits,Circuit Features',
            'delimiter' => 'required|in:Comma Delimited,Tab Delimited,Pipe Delimited',
            'file-upload' => 'required|file|mimetypes:text/plain,text/csv,text/tsv',
        ];
    }

    /**
     * Get custom messages for validation.
     *
     * If the upload fails because the file is too large, give a more relevant message.
     *
     * @return array
     */
    public function messages()
    {
        // I'm not sure how I feel about conditionally changing the message here, but it works.
        if (
            $this->file('file-upload') instanceof UploadedFile &&
            $this->file('file-upload')->getError() == UPLOAD_ERR_INI_SIZE
        ) {
            return [
                'file-upload.uploaded' => 'The :attribute may not be greater than ' . floor(UploadedFile::getMaxFilesize() / 1024) . ' kilobytes.',
            ];
        }

        return [];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'file-type' => 'File Type',
            'delimiter' => 'Delimiter',
            'file-upload' => 'File',
        ];
    }
}