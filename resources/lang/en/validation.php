<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'             => 'The :attribute must be accepted.',
    'active_url'           => 'The :attribute is not a valid URL.',
    'after'                => 'The :attribute must be a date after :date.',
    'after_or_equal'       => 'The :attribute must be a date after or equal to :date.',
    'alpha'                => 'The :attribute may only contain letters.',
    'alpha_dash'           => 'The :attribute may only contain letters, numbers, and dashes.',
    'alpha_num'            => 'The :attribute may only contain letters and numbers.',
    'array'                => 'The :attribute must be an array.',
    'before'               => 'The :attribute must be a date before :date.',
    'before_or_equal'      => 'The :attribute must be a date before or equal to :date.',
    'between'              => [
        'numeric' => 'The :attribute must be between :min and :max.',
        'file'    => 'The :attribute must be between :min and :max kilobytes.',
        'string'  => 'The :attribute must be between :min and :max characters.',
        'array'   => 'The :attribute must have between :min and :max items.',
    ],
    'boolean'              => 'The :attribute field must be true or false.',
    'confirmed'            => 'The :attribute confirmation does not match.',
    'date'                 => 'The :attribute is not a valid date.',
    'date_format'          => 'The :attribute does not match the format :format.',
    'different'            => 'The :attribute and :other must be different.',
    'digits'               => 'The :attribute must be :digits digits.',
    'digits_between'       => 'The :attribute must be between :min and :max digits.',
    'dimensions'           => 'The :attribute has invalid image dimensions.',
    'distinct'             => 'The :attribute field has a duplicate value.',
    'email'                => 'The :attribute must be a valid email address.',
    'exists'               => 'The selected :attribute is invalid.',
    'file'                 => 'The :attribute must be a file.',
    'filled'               => 'The :attribute field is required.',
    'image'                => 'The :attribute must be an image.',
    'in'                   => 'The selected :attribute is invalid.',
    'in_array'             => 'The :attribute field does not exist in :other.',
    'integer'              => 'The :attribute must be an integer.',
    'ip'                   => 'The :attribute must be a valid IP address.',
    'json'                 => 'The :attribute must be a valid JSON string.',
    'max'                  => [
        'numeric' => 'The :attribute may not be greater than :max.',
        'file'    => 'The :attribute may not be greater than :max kilobytes.',
        'string'  => 'The :attribute may not be greater than :max characters.',
        'array'   => 'The :attribute may not have more than :max items.',
    ],
    'mimes'                => 'The :attribute must be a file of type: :values.',
    'mimetypes'            => 'The :attribute must be a file of type: :values.',
    'min'                  => [
        'numeric' => 'The :attribute must be at least :min.',
        'file'    => 'The :attribute must be at least :min kilobytes.',
        'string'  => 'The :attribute must be at least :min characters.',
        'array'   => 'The :attribute must have at least :min items.',
    ],
    'not_in'               => 'The selected :attribute is invalid.',
    'numeric'              => 'The :attribute must be a number with no commas.',
    'present'              => 'The :attribute field must be present.',
    'regex'                => 'The :attribute format is invalid.',
    'required'             => 'The :attribute field is required.',
    'required_if'          => 'The :attribute field is required when :other is :value.',
    'required_unless'      => 'The :attribute field is required unless :other is in :values.',
    'required_with'        => 'The :attribute field is required when :values is present.',
    'required_with_all'    => 'The :attribute field is required when :values is present.',
    'required_without'     => 'The :attribute field is required when :values is not present.',
    'required_without_all' => 'The :attribute field is required when none of :values are present.',
    'same'                 => 'The :attribute and :other must match.',
    'size'                 => [
        'numeric' => 'The :attribute must be :size.',
        'file'    => 'The :attribute must be :size kilobytes.',
        'string'  => 'The :attribute must be :size characters.',
        'array'   => 'The :attribute must contain :size items.',
    ],
    'string'               => 'The :attribute must be a string.',
    'timezone'             => 'The :attribute must be a valid zone.',
    'unique'               => 'The :attribute has already been taken.',
    'uploaded'             => 'The :attribute failed to upload.',
    'url'                  => 'The :attribute format is invalid.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [
        'CircuitID'                 => 'Circuit ID',
        'AccountNum'                => 'Account #',
        'CarrierCircuitID'          => 'Carrier Circuit ID',
        'FeatureCode'               => 'Feature Code',
        'FeatureCost'               => 'Feature Cost',
        'Action'                    => 'Action',
        'CategoryName'              => 'Category Name',
        'ServiceTypeName'           => 'Service Type Name',
        'Description'               => 'Description',
        'StatusName'                => 'Status Name',
        'BillUnderBTN'              => 'Bill Under BTN',
        'Cost'                      => 'Cost',
        'ServiceAddress-Address1'   => 'Service Address Line 1',
        'ServiceAddress-Address2'   => 'Service Address Line 2',
        'ServiceAddress-City'       => 'Service Address City',
        'ServiceAddress-State'      => 'Service Address State',
        'ServiceAddress-Zip'        => 'Service Address Zip',
        'Email'                     => 'Email',
        'Note'                      => 'Note',
        'LocationAAddress-Address1' => 'Location A Address Line 1',
        'LocationAAddress-Address2' => 'Location A Address Line 2',
        'LocationAAddress-City'     => 'Location A Address City',
        'LocationAAddress-State'    => 'Location A Address State',
        'LocationAAddress-Zip'      => 'Location A Address Zip',
        'LocationZAddress-Address1' => 'Location Z Address Line 1',
        'LocationZAddress-Address2' => 'Location Z Address Line 2',
        'LocationZAddress-City'     => 'Location Z Address City',
        'LocationZAddress-State'    => 'Location Z Address State',
        'LocationZAddress-Zip'      => 'Location Z Address Zip',
        'QoS_CIR'                   => 'QoS/CIR',
        'PortSpeed'                 => 'Port Speed',
        'Mileage'                   => 'Mileage',
        'NetworkIPAddress'          => 'Network IP Address',
        'PointToNumber'             => 'Point To #',
        'SPID_Phone1'               => 'SPID/Phone 1',
        'SPID_Phone2'               => 'SPID/Phone 2',
        'AssignedToName'            => 'Assigned To Name',
        'DivisionDistrictCode'      => 'District Code',
        'DeviceType'                => 'Device Type',
        'DeviceMake'                => 'Device Make',
        'DeviceModel'               => 'Device Model',
        'IMEI'                      => 'IMEI',
        'SIM'                       => 'SIM',
        'LD_PIC'                    => 'LD PIC',
        'InstallationDT'            => 'Installation Date',
        'PhoneNumber'               => 'Phone #',
        'CarrierPhoneNum'           => 'Carrier Phone #',
        'DID'                       => 'DID',
        'DIDPrefix'                 => 'DID Range Prefix',
        'DIDFrom'                   => 'DID Range Start',
        'DIDTo'                     => 'DID Range End',
        'DIDNote'                   => 'DID Note',
        'BillingURL'                => 'Billing URL',
        'InvoiceAvailableDate'      => 'Invoice Available Date',
        'Username'                  => 'Username',
        'Password'                  => 'Password',
        'IsPaperless'               => 'Paperless',
        'PIN'                       => 'PIN',
        'DetailNotes'               => 'Note',
        'ACEITOrderNum'             => 'SNOW #',
        'CarrierOrderNum'           => 'Telco #',
        'OrderFiles.*'              => 'Files',
    ],

];
