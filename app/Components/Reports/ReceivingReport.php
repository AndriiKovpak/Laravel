<?php

namespace App\Components\Reports;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Class ReceivingReport
 * @package App\Components\Reports
 */
class ReceivingReport extends AbstractReport
{
    /**
     * @var string
     */
    protected $storedProcedure = 'spACOE_ReceivingReport';

    /**
     *
     */
    public function init()
    {
        $this->identify(false, self::CATEGORY_DO_NOT_RUN, 'Receiving Report', 55);
    }

    /**
     * Return the column names for if the report is empty.
     *
     * sp_describe_first_result_set cannot produce this one because of dynamic SQL.
     *
     * @return array
     */
    protected function fallbackColumns()
    {
        return [
            'Obil No',
            'Reference',
            'Goods/Serv',
            'Acceptance',
            'Desc',
            'Remarks',
            'Line Amt',
            'Research Notes',
        ];
    }

    /**
     * @return source
     */
    protected function source()
    {
        ini_set('memory_limit', '2048M');
        ini_set('max_execution_time', '1500');
        return DB::select(
            'SET NOCOUNT ON; EXEC :sp',
            [
                'sp' => $this->storedProcedure,
            ]
        );
    }
}