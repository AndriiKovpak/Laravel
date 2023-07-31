<?php

namespace App\Models;

/**
 * Class Circuit
 * @package App\Models
 */
/**
 * Class Circuit
 * @package App\Models
 */
class Circuit extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'Circuits';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'CircuitID';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'BTNAccountID', 'CircuitID', 'CarrierCircuitID', 'DivisionDistrictID', 'BillUnderBTN', 'ServiceType', 'CategoryID',
        'Status', 'UpdatedDate', 'BillingStartDate', 'InstallationDT', 'DisconnectDate','Cost', 'UpdatedByUserID', 'TelcoNum', 'SNOWTicketNum', 'Description2'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'CategoryID'        =>  'integer',
        'BillingStartDate'  =>  'datetime',
        'InstallationDT'    =>  'datetime',
        'DisconnectDate'    =>  'datetime',
    ];

    /**
     * Declare relation methods
     *
     * @var array
     */
    private static $categoryMethods = [
        Category::VOICE     =>  'CircuitVoice',
        Category::DATA      =>  'CircuitData',
        Category::SATELLITE =>  'CircuitSatellite'
    ];

    /**
     * Return the CircuitCMDBUpdates for this Circuit
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function CMDBUpdates()
    {
        return $this->hasMany(\App\Models\CircuitCMDBUpdate::class, 'CircuitID', 'CircuitID');
    }

    /**
     * Return the CircuitDIDs for this Circuit
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function DIDs()
    {
        return $this->hasMany(\App\Models\CircuitDID::class, 'CircuitID', 'CircuitID');
    }

    /**
     * Return the CircuitFeatures for this Circuit
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function Features()
    {
        return $this->hasMany(\App\Models\CircuitFeature::class, 'CircuitID', 'CircuitID');
    }

    /**
     * Return the CircuitMACs for this Circuit
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function MACs()
    {
        return $this->hasMany(\App\Models\CircuitMAC::class, 'CircuitID', 'CircuitID');
    }

    /**
     * Return the CircuitNotes for this Circuit
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function Notes()
    {
        return $this->hasMany(\App\Models\CircuitNote::class, 'CircuitID', 'CircuitID');
    }

    /**
     * Return the BTNAccount
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function BTNAccount()
    {
        return $this->belongsTo(\App\Models\BTNAccount::class, 'BTNAccountID', 'BTNAccountID');
    }

    /**
     * Return the DivisionDistrict
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function DivisionDistrict()
    {
        return $this->belongsTo(\App\Models\DivisionDistrict::class, 'DivisionDistrictID', 'DivisionDistrictID');
    }

    /**
     * Return the ServiceType
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function Service()
    {
        return $this->belongsTo(\App\Models\ServiceType::class, 'ServiceType', 'ServiceType');
    }

    /**
     * Return the Category
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function Category()
    {
        return $this->belongsTo(\App\Models\Category::class, 'CategoryID', 'CategoryID');
    }

    /**
     * Return the StatusType
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function StatusType()
    {
        return $this->belongsTo(\App\Models\BTNStatusType::class, 'Status', 'BTNStatus');
    }

    /**
     * Return the CircuitData
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function CircuitData()
    {
        return $this->hasOne(\App\Models\CircuitData::class, 'CircuitID', 'CircuitID');
    }

    /**
     * Return the CircuitVoice
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function CircuitVoice()
    {
        return $this->hasOne(\App\Models\CircuitVoice::class, 'CircuitID', 'CircuitID');
    }

    /**
     * Return the CircuitSatellite
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function CircuitSatellite()
    {
        return $this->hasOne(\App\Models\CircuitSatellite::class, 'CircuitID', 'CircuitID');
    }

    /**
     * Return related category's data
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function CategoryData()
    {
        switch ($this->getAttribute('CategoryID')) {

            case Category::VOICE:
                return $this->CircuitVoice();

            case Category::DATA:
                return $this->CircuitData();

            case Category::SATELLITE:
                return $this->CircuitSatellite();
        }
    }

    /**
     * Return Category method name
     *
     * @param $CategoryID
     * @return string
     */
    public static function getCategoryMethod($CategoryID)
    {
        return self::$categoryMethods[$CategoryID];
    }

    public function getCostAttribute($value) {
        return Util::formatCurrency($value);
    }
}
