<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/dashboard';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });

        Route::model('inventory', \App\Models\BTNAccount::class);
        Route::model('order', \App\Models\BTNAccountOrder::class);
        Route::model('accounts_payable', \App\Models\InvoiceAP::class);
        Route::model('circuit', \App\Models\Circuit::class);
        Route::model('did', \App\Models\CircuitDID::class);
        Route::model('btn_account_mac', \App\Models\BTNAccountMAC::class);
        Route::model('circuit_mac', \App\Models\CircuitMAC::class);
        Route::model('csr', \App\Models\BTNAccountCSR::class);
        Route::model('csr_file', \App\Models\BTNAccountCSRFile::class);
        Route::model('order_file', \App\Models\BTNAccountOrderFile::class);
        Route::model('carrier', \App\Models\Carrier::class);
        Route::model('contact', \App\Models\CarrierContact::class);
        Route::model('ftp_folder', \App\Models\FTPFolder::class);
        Route::model('division_district', \App\Models\DivisionDistrict::class);
        Route::model('service_type', \App\Models\ServiceType::class);
        Route::model('feature', \App\Models\FeatureType::class);
        Route::model('favorite_report', \App\Models\Report::class);
        Route::model('scanned_image', \App\Models\ScannedImage::class);

        parent::boot();
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}
