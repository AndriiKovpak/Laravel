<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class BTNAccount extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'BTNAccounts';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'BTNAccountID';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'BTN',
        'AccountNum',
        'CarrierID',
        'Status',
        'SiteAddressID',
        'BillingAddressID',
        'DivisionDistrictID',
        'IsLegacy',
        'IsDoNotPay',
        'IsVIP',
        'UpdatedByUserID',
        'DisconnectDate',
    ];


    /**
     * Return the most recent InvoiceAP record for this BTNAccount
     *
     * @return \App\Models\InvoiceAP
     */
    public function MostRecentInvoice()
    {
        return $this->AccountsPayable()
            ->where('ProcessedMethod', '<>', 4) // Exclude In Process
            ->latest('BillDate')
            ->first();
    }

    /**
     * Return the BTNAccountCSRs for this BTNAccount
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function CSRs()
    {
        return $this->hasMany(\App\Models\BTNAccountCSR::class, 'BTNAccountID', 'BTNAccountID');
    }

    /**
     * Return the BTNAccountOrders for this BTNAccount
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function Orders()
    {
        return $this->hasMany(\App\Models\BTNAccountOrder::class, 'BTNAccountID', 'BTNAccountID');
    }

    /**
     * Return the Carrier
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function Carrier()
    {
        return $this->belongsTo(\App\Models\Carrier::class, 'CarrierID', 'CarrierID');
    }

    /**
     * Return the Carrier Billing Details
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function CarrierDetails()
    {
        return $this->hasOne(\App\Models\BTNAccountCarrierDetails::class, 'BTNAccountID', 'BTNAccountID');
    }

    /**
     * Return the BTNStatusType
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function BTNStatusType()
    {
        return $this->belongsTo(\App\Models\BTNStatusType::class, 'Status', 'BTNStatus');
    }

    /**
     * Return the SiteAddress
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function SiteAddress()
    {
        return $this->belongsTo(\App\Models\Address::class, 'SiteAddressID', 'AddressID');
    }

    /**
     * Return the BillingAddress
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function BillingAddress()
    {
        return $this->belongsTo(\App\Models\Address::class, 'AddressID', 'BillingAddressID');
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
     * Return the DivisionDistrict
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function UpdatedByUser()
    {
        return $this->hasOne(\App\Models\User::class, 'UserID', 'UpdatedByUserID');
    }

    /**
     * Return the Circuits for this BTNAccount
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function Circuits()
    {
        return $this->hasMany(\App\Models\Circuit::class, 'BTNAccountID', 'BTNAccountID');
    }

    /**
     * Return the ScannedImages for this BTNAccount
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ScannedImages()
    {
        return $this->hasMany(\App\Models\ScannedImage::class, 'BTNAccountID', 'BTNAccountID');
    }

    /**
     * Return the Accounts Payable for this BTNAccount
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function AccountsPayable()
    {
        return $this->hasMany(\App\Models\InvoiceAP::class, 'BTNAccountID', 'BTNAccountID')->where('ProcessedMethod', '<>', 5);
    }

    /**
     * Return all the MACs (of all the Circuits) of this BTN
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function MACs()
    {
        return $this->hasMany(\App\Models\BTNAccountMAC::class, 'BTNAccountID', 'BTNAccountID');
    }

    /**
     * Return Notes of this BTN
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function Notes()
    {
         return $this->hasMany(\App\Models\BTNAccountNote::class, 'BTNAccountID', 'BTNAccountID')->orderBy('Updated_at', 'desc');
    }

    /*
     * TODO: Not sure of the best place to put this, so it is here for now.
     * Return count of BTNAccounts with Status of 3 (Inactive - Disconnected)
     *
     * @return total
     */
    public static function getDisconnectsThisMonth(){

        if (date('j') <= 25) {
            $disconnectRange = [
                Carbon::now()->startOfMonth()->subMonth()->day(26), // 00:00:00 on 26th of last month
                Carbon::now()->endOfMonth()->day(25), // 23:59:59 on 25th of this month
            ];
        } else {
            $disconnectRange = [
                Carbon::now()->startOfMonth()->day(26), // 00:00:00 on 26th of this month
                Carbon::now()->addMonth()->endOfMonth()->day(25), // 23:59:59 on 25th of next month
            ];
        }

        $query = BTNAccount::query();
        $query
            ->join('BTNStatusTypes', 'BTNAccounts.Status', '=', 'BTNStatusTypes.BTNStatus')
            ->leftJoin('Circuits', 'BTNAccounts.BTNAccountID', '=', 'Circuits.BTNAccountID')
            ->leftJoin('BTNAccountMACs', 'BTNAccounts.BTNAccountID', '=', 'BTNAccountMACs.BTNAccountID')
            ->leftJoin('CircuitMACs', 'Circuits.CircuitID', '=', 'CircuitMACs.CircuitID')
            ->where(function ($query) use ($disconnectRange) {
                $query->whereBetween('BTNAccountMACs.DisconnectDate', $disconnectRange)
                    ->orWhereBetween('CircuitMACs.DisconnectDate', $disconnectRange)
                    ->orWhere(function ($query) use ($disconnectRange) {
                        $query->where('BTNAccounts.Status', '=', 3)
                            ->whereBetween('BTNAccounts.DisconnectDate', $disconnectRange);
                    });
            })
            ->where('BTNStatusTypes.IsDisplay', true);

        return $query->distinct()->count('BTNAccounts.BTNAccountID');

    }

    /*
     * TODO: Not sure of the best place to put this, so it is here for now.
     *
     * @return total
     */
    public static function getExpirationsThisMonth(){

        $expireRange = [
            Carbon::now()->startOfMonth(), // 00:00:00 on first of this month
            Carbon::now()->endOfMonth(), // 23:59:59 on last of this month
        ];

        $query = BTNAccount::query();
        $query
            ->join('BTNStatusTypes', 'BTNAccounts.Status', '=', 'BTNStatusTypes.BTNStatus')
            ->leftJoin('Circuits', 'BTNAccounts.BTNAccountID', '=', 'Circuits.BTNAccountID')
            ->leftJoin('BTNAccountMACs', 'BTNAccounts.BTNAccountID', '=', 'BTNAccountMACs.BTNAccountID')
            ->leftJoin('CircuitMACs', 'Circuits.CircuitID', '=', 'CircuitMACs.CircuitID')
            ->where(function ($query) use ($expireRange) {
                $query->whereBetween('BTNAccountMACs.ContractExpDate', $expireRange)
                    ->orWhereBetween('CircuitMACs.ContractExpDate', $expireRange);
            })
            ->where('BTNStatusTypes.IsDisplay', true);

        return $query->distinct()->count('BTNAccounts.BTNAccountID');

    }

}
