<?php

namespace App\Services\Settings\FileUploads;

use App\Exceptions\FileUploadException;
use App\Models\FileUpload;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Validation\ValidationException;

/**
 * Class BaseFileUploadsService
 * @package App\Services\Settings
 */
abstract class BaseFileUploadsService
{
    use ValidatesRequests;

    /**
     * User-readable name of the file type
     *
     * @var string
     */
    protected $fileType = '';

    /**
     * Array of column names for each row.
     *
     * This is used by makeRowAssociativeArray() so validateRow() and processRow() can refer to the columns by name instead of numeric index.
     *
     * I was considering making this an associative array that could also be used for the validation attributes in validateRow(),
     * but I decided not to. If we do that in the future, makeRowAssociativeArray() will need to use array_keys, of course.
     *
     * @var array
     */
    protected $columnNames = [];

    /**
     * Associative array used to avoid duplicate or contradicting actions
     *
     * @var array
     */
    protected $rowKeys = [];

    /**
     * Validate a row
     *
     * @param array $fileUploadRow This array holds the information being validated.
     *
     * @return void An exception is thrown if validation fails.
     */
    abstract protected function validateRow(array $fileUploadRow);

    /**
     * Process a row
     *
     * @param array $fileUploadRow This array holds the information to be stored/updated/deleted in the database.
     *
     * @return void
     */
    abstract protected function processRow(array $fileUploadRow);

    /**
     * @param $uploadedFile
     * @param $delimiter
     * @throws FileUploadException
     */
    public function processFile($uploadedFile, $delimiter)
    {
        $original_auto_detect_line_endings = ini_get('auto_detect_line_endings');
        ini_set('auto_detect_line_endings', true); // May be necessary if file is uploaded from a Mac.
        // dd($uploadedFile, $delimiter, $original_auto_detect_line_endings);

        try {
            $fileUpload = fopen($uploadedFile, 'r');
            if (!$fileUpload) { // This block seems to be unneeded. I wonder if Laravel is configured to turn errors into exceptions.
                throw new \Exception(json_encode(error_get_last()));
            }
        } catch (\Exception $e) {
            // reset auto_detect_line_endings.
            ini_set('auto_detect_line_endings', $original_auto_detect_line_endings); // This probably doesn't have to be set back... But it seems like the right thing to do...

            FileUpload::create([
                'FileType' => $this->fileType,
                'RecordCount' => 0,
                'ErrorCount' => 0,
                'ErrorCode' => $e->getMessage(),
            ]);

            throw new FileUploadException('The File could not be opened.');
        }

        try {
            // Read file once for validation
            // This is done before any processing so an error does not end in a partially processed file.
            $validationRowCounter = 0;
            $validationErrorCounter = 0;
            $validationErrors = [];
            while (($fileUploadRow = fgetcsv($fileUpload, 0, $delimiter)) !== false) {
                $validationRowCounter++; // Increment first to start at one and have it end on the number processed.

                try {
                    $this->validateRow($fileUploadRow);
                } catch (ValidationException $e) {
                    $validationErrorCounter++;
                    // dd($e->errors());
                    foreach ($e->errors() as $attribute => $error_messages) {
                        foreach ($error_messages as $message) {
                            $validationErrors[$attribute][$message][] = $validationRowCounter;
                        }
                    }
                }
            }

            // Validation failed.
            if (!empty($validationErrors)) {
                $displayErrors = [];

                foreach ($validationErrors as $attribute => $error_messages) {
                    foreach ($error_messages as $message => $rows) {
                        // List rows as Row 1; Rows 1, 2; Rows 1-3; Rows 1-3, 5, 7-9, 11, 12, 14
                        $segmentStart = null;
                        $segmentEnd = null;
                        $rowString = '';
                        $rowCount = count($rows);
                        foreach ($rows as $index => $row) {
                            if ($index == 0) { // First (or only) row
                                $rowString .= $row;
                                $segmentStart = $row;
                            } else if ($segmentEnd != $row - 1 || $index == $rowCount - 1) { // Last row or new segment
                                if ($segmentStart == $segmentEnd) { // Previous segment had one row
                                    $rowString .= ', ' . $row; // 1, 3
                                } else if ($segmentEnd == $row - 1) { // Last row is part of segment
                                    $rowString .= '-' . $row; // 1-3
                                } else if ($segmentStart == $segmentEnd - 1) { // Previous segment had two rows
                                    $rowString .= ', ' . $segmentEnd . ', ' . $row; // 1, 2, 4
                                } else { // Previous segment had several rows
                                    $rowString .= '-' . $segmentEnd . ', ' . $row; // 1-3, 5
                                }
                                $segmentStart = $row;
                            }
                            $segmentEnd = $row;
                        }

                        $displayErrors[$attribute][] = 'Row' . ($rowCount > 1 ? 's ' : ' ') . $rowString . ': ' . $message;
                    }
                }

                FileUpload::create([
                    'FileType'    => $this->fileType,
                    'RecordCount' => $validationRowCounter,
                    'ErrorCount'  => $validationErrorCounter,
                    'ErrorCode'   => json_encode($displayErrors),
                ]);

                throw new FileUploadException($displayErrors);
            }

            // Read file again for processing
            rewind($fileUpload);
            $processingRowCounter = 0;
            try {
                while (($fileUploadRow = fgetcsv($fileUpload, 0, $delimiter)) !== false) {
                    $processingRowCounter++; // Increment first to start at one and have it end on the number processed.

                    $this->processRow($fileUploadRow);
                }
            } catch (\Exception $e) {
                FileUpload::create([
                    'FileType'    => $this->fileType,
                    'RecordCount' => $processingRowCounter - 1, // Don't count error row
                    'ErrorCount'  => 1,
                    'ErrorCode'   => $e->getMessage(),
                ]);

                throw new FileUploadException('The File failed on row ' . $processingRowCounter . '.');
            }

            // Success
            FileUpload::create([
                'FileType'    => $this->fileType,
                'RecordCount' => $processingRowCounter,
                'ErrorCount'  => 0,
                'ErrorCode'   => 'Success',
            ]);
        } finally {
            // Close file and reset auto_detect_line_endings.
            fclose($fileUpload);
            ini_set('auto_detect_line_endings', $original_auto_detect_line_endings); // This probably doesn't have to be set back... But it seems like the right thing to do...
        }
    }

    /**
     * Turn a row into an associative array
     *
     * Make sure every key is set. Trim whitespace. Set empty string entries to null.
     *
     * Note: array_combine() cannot be used because the arrays may have different lengths.
     *
     * @param array $fileUploadRow Index array
     *
     * @return array Associative array
     */
    protected function makeRowAssociativeArray(array $fileUploadRow)
    {
        $associativeArray = [];
        foreach ($this->columnNames as $index => $columnName) {
            $associativeArray[$columnName] = isset($fileUploadRow[$index]) ? trim($fileUploadRow[$index]) : null;
            $associativeArray[$columnName] = ($associativeArray[$columnName] !== '') ? $associativeArray[$columnName] : null;
        }
        return $associativeArray;
    }

    /**
     * Validate an array (instead of a request)
     *
     * Adapted from \Illuminate\Foundation\Validation\ValidatesRequests->validate()
     *
     * @param array $array This array holds the information being validated.
     * @param array $rules This array holds the rules used to validate $array.
     * @param array $messages This array holds the messages to display for each rule.
     * @param array $customAttributes This array holds the names to display for each attribute.
     *
     * @throws ValidationException
     *
     * @return void An exception is thrown if validation fails.
     */
    public function validateArray(array $array, array $rules, array $messages = [], array $customAttributes = [])
    {
        $validator = $this->getValidationFactory()->make($array, $rules, $messages, $customAttributes);

        if ($validator->fails()) {
            // This doesn't need to generate a request, so this part differs from the other function.
            throw new ValidationException($validator);
        }
    }
}
