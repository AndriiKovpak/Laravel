<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailMessage extends Model
{
    private $EmailMessageQueueID;
    private $UserID;
    private $EmailTemplateID;
    private $ToEmailAddress;
    private $FromEmailAddress;
    private $Subject;
    private $IsSent;
    private $Status;
    private $Data;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'UserID',
        'EmailTemplateID',
        'ToEmailAddress',
        'FromEmailAddress',
        'Subject',
        'IsSent',
        'Status',
        'Data'
    ];

    protected $primaryKey = 'EmailMessageQueueID';
    protected $table = 'EmailMessageQueue';

    public function User()
    {
        return $this->belongsTo('App\Models\User', 'UserID', 'UserID');
    }

    public function EmailTemplate()
    {
        return $this->belongsTo('App\Models\EmailTemplate', 'EmailTemplateID', 'EmailTemplateID');
    }

    public function scopeActive($query)
    {
        return $query->where('Status', '1');
    }

    public function scopeInactive($query)
    {
        return $query->where('Status', '2');
    }

    public function scopeReturned($query)
    {
        return $query->where('Status', '3');
    }

    public function scopeInvalid($query)
    {
        return $query->where('Status', '4');
    }

    public function scopeProcessing($query)
    {
        return $query->where('Status', '5');
    }
    public function scopeError($query)
    {
        return $query->where('Status', '6');
    }


    public function scopeQueued($query)
    {
        return $query->where('Status', '1')->where('IsSent', '0');
    }

    // TODO: Move values to config
    public function getStatusName()
    {
        switch ($this['Status']) {
            case 1:
                return 'Active';
            case 2:
                return 'Inactive';
            case 3:
                return 'Returned';
            case 4:
                return 'Invalid';
            case 5:
                return 'Processing';
            default:
                return 'Error';
        }
    }
}
