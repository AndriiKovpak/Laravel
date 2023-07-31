<?php

namespace App\Services\View;

use App\Models\Circuit;
use App\Models\BTNAccount;
use Illuminate\Support\Arr;

/**
 * Class BTN
 * @package App\Services\View
 */
class BTN
{
    /**
     * @var BTNAccount|null
     */
    private $BTNAccount;

    /**
     * @var \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    private $Circuits;

    /**
     * BTN constructor.
     */
    public function __construct()
    {
        $this->BTNAccount = request()->route('inventory');

        if (!is_a($this->BTNAccount, BTNAccount::class)) {

            $this->BTNAccount = null;
        }

        $this->Circuits = $this->BTNAccount->Circuits()->paginate(null, ['CircuitID', 'CategoryID', 'ServiceType'], 'page');
    }

    /**
     * Return Circuits of a BTN Account.
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function circuits()
    {
        return $this->Circuits;
    }

    /**
     * Return BTN Account Number value
     *
     * @return string|null
     */
    public function getNumber()
    {
        return Arr::get($this->BTNAccount, 'BTN');
    }

    /**
     *
     */
    public function getAccountNumber()
    {
        return $this->BTNAccount->AccountNum;
    }

    /**
     * Check if a given Circuit is active one from the URL
     *
     * @param Circuit $circuit
     * @return bool
     */
    public function isActiveCircuit(Circuit $circuit)
    {
        $c = request()->route('circuit');

        return (!is_null($c)) && $circuit->getKey() == $c->getKey();
    }
}
