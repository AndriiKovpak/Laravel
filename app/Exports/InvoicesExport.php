<?php

namespace App\Exports;

use App\Models\BTNAccount;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;

class InvoicesExport implements FromQuery
{
    use Exportable;

    protected $storedProcedure = 'spACOE_RptCMDBInventoryReportAllOld';

    public function query()
    {
        return BTNAccount::query();
    }
}

