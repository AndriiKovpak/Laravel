<?php

namespace App\Http\Middleware;

use Closure;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RouteModelRelationship
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if (
            (
                // dashboard/carriers/{carrier}
                !empty($request->carrier)
                && (
                    // dashboard/carriers/{carrier}/contact/{contact}
                    !empty($request->contact)
                    && $request->contact->CarrierID != $request->carrier->CarrierID
                )
            )
            || (
                // dashboard/inventory/{inventory}
                !empty($request->inventory)
                && (
                    (
                        // dashboard/inventory/{inventory}/accounts-payable/{accounts_payable}
                        !empty($request->accounts_payable)
                        && $request->accounts_payable->BTNAccountID != $request->inventory->BTNAccountID
                    )
                    || (
                        // dashboard/inventory/{inventory}/circuits/{circuit}
                        !empty($request->circuit)
                        && (
                            $request->circuit->BTNAccountID != $request->inventory->BTNAccountID
                            || (
                                // dashboard/inventory/{inventory}/circuits/{circuit}/dids/{did}
                                !empty($request->did)
                                && $request->did->CircuitID != $request->circuit->CircuitID
                            )
                            || (
                                // dashboard/inventory/{inventory}/circuits/{circuit}/mac/{circuit_mac}
                                !empty($request->circuit_mac)
                                && $request->circuit_mac->CircuitID != $request->circuit->CircuitID
                            )
                        )
                    )
                    || (
                        // dashboard/inventory/{inventory}/csr/{csr}
                        !empty($request->csr)
                        && (
                            $request->csr->BTNAccountID != $request->inventory->BTNAccountID
                            || (
                                // dashboard/inventory/{inventory}/csr/{csr}/file/{csr_file?}
                                !empty($request->csr_file)
                                && $request->csr_file->BTNAccountCSRID != $request->csr->BTNAccountCSRID
                            )
                        )
                    )
                    || (
                        // dashboard/inventory/{inventory}/mac/{btn_account_mac}
                        !empty($request->btn_account_mac)
                        && $request->btn_account_mac->BTNAccountID != $request->inventory->BTNAccountID
                    )
                )
            )
        ) {
            throw new NotFoundHttpException('Invalid model relationship');
        }

        return $next($request);
    }
}
