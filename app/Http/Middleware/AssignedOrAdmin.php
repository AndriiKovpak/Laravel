<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AssignedOrAdmin
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
        if ($request->inventory && Auth::user()->cant('BTNAccount.view', $request->inventory)) {
            throw new NotFoundHttpException('Access denied');
        }
        if ($request->invoice && Auth::user()->cant('InvoiceAP.view', $request->invoice)) {
            throw new NotFoundHttpException('Access denied');
        }
        if ($request->scanned_image && Auth::user()->cant('ScannedImage.view', $request->scanned_image)) {
            throw new NotFoundHttpException('Access denied');
        }

        return $next($request);
    }
}
