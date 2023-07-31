<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Models\InvoiceAP;
use App\Models\BTNAccount;
use App\Models\ScannedImage;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        $this->registerPolicies();

        // Considering that we have custom tables relate to user's staff
        // we need to create own own UserProvider
        $this->app['auth']->provider('main', function($app, $config) {

            return new \App\Components\Core\Auth\UserProvider($app['hash'], $config['model']);
        });

        Gate::define('BTNAccount.view', function (User $user, BTNAccount $BTNAccount) {
            return (
                $user->SecurityGroup == 1
                || (
                    !empty($BTNAccount->DivisionDistrictID)
                    && ($user->DivisionDistricts()->where('Users_DivisionDistricts.DivisionDistrictID', $BTNAccount->DivisionDistrictID)->count()
                    || $BTNAccount->Circuits()->whereIn('DivisionDistrictID', $user->DivisionDistricts()->pluck('Users_DivisionDistricts.DivisionDistrictID')->toArray())->count()
                    )
                )
            );
        });

        Gate::define('InvoiceAP.view', function (User $user, InvoiceAP $InvoiceAP) {
            return (
                $user->SecurityGroup == 1
                || (
                    !empty($InvoiceAP->BTNAccount->DivisionDistrictID)
                    && $user->DivisionDistricts()->where('Users_DivisionDistricts.DivisionDistrictID', $InvoiceAP->BTNAccount->DivisionDistrictID)->count()
                )
            );
        });

        Gate::define('ScannedImage.view', function (User $user, ScannedImage $ScannedImage) {
            return (
                $user->SecurityGroup == 1
                || (
                    !empty($ScannedImage->BTNAccount->DivisionDistrictID)
                    && $user->DivisionDistricts()->where('Users_DivisionDistricts.DivisionDistrictID', $ScannedImage->BTNAccount->DivisionDistrictID)->count()
                )
            );
        });

        Gate::define('edit', function (User $user) {
            return $user->SecurityGroup == 1;
        });
    }
}
