<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    private $EmailTemplateID;
    private $ViewName;
    private $Description;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ViewName',
        'Description',
    ];

    protected $primaryKey = 'EmailTemplateID';
    protected $table = 'EmailTemplates';

    public function EmailMessages()
    {
        return $this->hasMany('App\Models\EmailMessage', 'EmailTemplateID', 'EmailTemplateID');
    }
}
