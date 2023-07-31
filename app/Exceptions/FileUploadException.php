<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Arr;

class FileUploadException extends Exception
{
    /**
     * Detailed error messages for display
     *
     * @var array
     */
    public $messages = [];

    /**
     * FileUploadException constructor.
     *
     * @param array|string $messages
     */
    public function __construct($messages)
    {
        parent::__construct('The file upload failed.');

        Arr::wrap($messages);

        $this->messages = $messages;
    }

    /**
     * Get the error messages for display.
     *
     * @return array
     */
    public function getMessages()
    {
        return $this->messages;
    }
}
