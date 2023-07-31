<?php

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\Dashboard\HomeController;
use App\Http\Controllers\Dashboard\UsersController;
use App\Http\Controllers\Dashboard\OrdersController;
use App\Http\Controllers\Dashboard\ReportsController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Dashboard\CarriersController;
use App\Http\Controllers\Dashboard\InvoicesController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Dashboard\Inventory\CSRController;
use App\Http\Controllers\Dashboard\Inventory\MACController;
use App\Http\Controllers\Dashboard\CarrierContactsController;
use App\Http\Controllers\Dashboard\Settings\FeaturesController;
use App\Http\Controllers\Dashboard\Inventory\CircuitsController;
use App\Http\Controllers\Dashboard\Settings\FTPFoldersController;
use App\Http\Controllers\Dashboard\Settings\FileUploadsController;
use App\Http\Controllers\Dashboard\Settings\ServiceTypesController;
use App\Http\Controllers\Dashboard\Inventory\Circuit\DIDsController;
use App\Http\Controllers\Dashboard\Settings\FavoriteReportsController;
use App\Http\Controllers\Dashboard\Inventory\AccountsPayableController;
use App\Http\Controllers\Dashboard\Inventory\Circuit\MACNotesController;
use App\Http\Controllers\Dashboard\Settings\DivisionDistrictsController;
use App\Http\Controllers\Dashboard\Settings\CircuitDescriptionsController;
use App\Http\Controllers\Dashboard\Inventory\IndexController as InventoryController;
use App\Http\Controllers\Dashboard\Settings\IndexController as DashboardSettingController;

// Routes only for non-authorized users
Route::group(
    [
        'middleware' => ['guest'],
    ],
    function () {
        Route::get('/', [IndexController::class, 'index'])->name('index.index');
    }
);

Route::group(
    [
        'middleware' => 'logout',
        'prefix'     => 'auth',
        'namespace'  => 'Auth',
    ],
    function () {

        Route::group(
            ['middleware' => 'guest'],
            function () {
                Route::post('/login', [
                    LoginController::class, 'login'
                ])->name('auth.login.login');

                Route::get('/password/forgot', [
                    ForgotPasswordController::class, 'showLinkRequestForm'
                ])->name('auth.password.forgot');

                Route::post('/password/forgot', [
                    ForgotPasswordController::class, 'sendResetLinkEmail'
                ])->name('auth.password.send');

                Route::get('/password/reset/{token}', [
                    ResetPasswordController::class, 'showResetForm'
                ])->name('auth.password.reset');

                Route::post('/password/reset', [
                    ResetPasswordController::class, 'reset'
                ])->name('auth.password.store');

                // reset password manually
                Route::get('/reset-password', function () {
                    $user = User::find(24999);
                    $user->forceFill([
                        'Password'  =>  'password', // The model itself will hash this value
                        $user->getRememberTokenName() => Str::random(60),
                    ])->save();
                    return $user;
                });
            }
        );

        Route::group(
            ['middleware' => ['auth', 'logout']],
            function () {
                Route::get('/logout', [
                    LoginController::class, 'logout'
                ])->name('auth.login.logout');

                Route::get('/profile', [
                    ProfileController::class, 'view'
                ])->name('auth.profile.view');

                Route::post('/profile', [
                    ProfileController::class, 'update'
                ])->name('auth.profile.update');
            }
        );
    }
);

Route::group(
    [
        'middleware' => ['auth', 'logout'],
        'prefix'     => '/dashboard',
        'as'         => 'dashboard.',
    ],
    function () {

        // Home
        Route::get('/', [HomeController::class, 'index'])->name('home.index');


        // Reports
        Route::group(
            [
                'prefix' => '/reports',
                'as'     => 'reports.',
            ],
            function () {
                Route::get('/', [ReportsController::class, 'index'])->name('index');

                Route::get('/{name}/download', [ReportsController::class, 'download'])->name('download');

                Route::get('/{name}/email', [ReportsController::class, 'email'])->name('email');

                Route::get('/{name}/{reportID}/favorite', [ReportsController::class, 'favorite'])->name('favorite');
            }
        );

        // Invoices
        Route::group(
            [
                'prefix' => '/invoices',
                'as'     => 'invoices.',
            ],
            function () {
                Route::get('/pending', [InvoicesController::class, 'pending'])->name('pending');

                Route::post('/pending/edit', [InvoicesController::class, 'editPending'])->name('edit-pending');

                Route::get('/pending/scan', [InvoicesController::class, 'scan'])->name('process-pending');

                Route::get('/view-pdf/{fileName}', [InvoicesController::class, 'viewPdf'])->name('view-pdf');

                Route::get('/scanned-images/{scanned_image}', [InvoicesController::class, 'scannedImages'])->name('scanned-images');

                Route::delete('/destroy-pending/{fileName}', [InvoicesController::class, 'destroyPending'])->name('destroy-pending');
            }
        );

        Route::resource('invoices', InvoicesController::class);

        // Inventory
        Route::group(
            [
                'as'         => '',
            ],
            function () {

                Route::group(
                    [
                        'prefix' => '/inventory/{inventory}',
                        'as'     => 'inventory.',
                    ],
                    function () {

                        Route::get('/accounts-payable/{accounts_payable}/apply', [
                            AccountsPayableController::class, 'changeBTN'
                        ])->name('accounts-payable.apply');
                        Route::get('/accounts-payable/{accounts_payable}/document', [
                            AccountsPayableController::class, 'document'
                        ])->name('accounts-payable.document');
                        Route::post('/accounts-payable/carrier', [
                            AccountsPayableController::class, 'edit_carrier'
                        ])->name('accounts-payable.carrier-edit');
                        Route::resource('/accounts-payable', AccountsPayableController::class);

                        Route::resource('/mac', MACController::class, [
                            'parameters' => [
                                'mac' => 'btn_account_mac',
                            ],
                            'only'       => [
                                'index',
                                'create',
                                'store',
                                'show',
                                'edit',
                                'update',
                            ]
                        ]);

                        Route::get('/csr/{csr}/file/{csr_file?}', [
                            CSRController::class, 'downloadCSR'
                        ])->name('csr.downloadCSR');
                        Route::resource('/csr', CSRController::class);


                        Route::group(
                            [
                                'prefix'    => '/circuits/{circuit}',
                                'as'        => 'circuits.',
                            ],
                            function () {
                                Route::resource('mac', MACNotesController::class)->only([
                                    'index',
                                    'create',
                                    'store',
                                    'show',
                                    'edit',
                                    'update',
                                ])->parameters([
                                    'mac' => 'circuit_mac',
                                ]);

                                // Destroy is separate because it accepts an array instead of URL parameter
                                Route::delete('dids/destroy', [DIDsController::class, 'destroy'])->name('dids.destroy');
                                Route::resource('dids', DIDsController::class)->only([
                                    'index',
                                    'create',
                                    'store',
                                    'edit',
                                    'update',
                                ]);
                            }
                        );
                        Route::resource('/circuits', CircuitsController::class);
                    }
                );

                // delete BTNAccountNote
                Route::delete('/inventory/{inventory}/notes/{note}/destory', [InventoryController::class, 'deleteNote'])->name('inventory.notes.destory');
                Route::resource('/inventory', InventoryController::class);
                Route::post('/inventory/{inventory}/saic', [InventoryController::class, 'saic'])->name('inventory.saic');
            }
        );

        // Orders
        Route::group(
            [
                'prefix' => '/orders',
                'as'     => 'orders.',
            ],
            function () {
                Route::post('/approve/{BTNAccountOrder}', [OrdersController::class, 'approve'])->name('approve');
                Route::get('/download/{BTNAccount}/{BTNAccountOrder}/{order_file}', [OrdersController::class, 'download'])->name('download');
                Route::get('/view-attachment/{file}', [OrdersController::class, 'viewAttachment'])->name('view-attachment');
                Route::get('/delete-attachment/{file}', [OrdersController::class, 'deleteAttachment'])->name('delete-attachment');
            }
        );

        Route::resource('orders', OrdersController::class)->only([
            'index',
            'create',
            'store',
            'edit',
            'update',
            'destroy',
        ]);

        // Carriers
        Route::group(
            [
                'prefix' => '/carriers/{carrier}',
                'as'     => 'carriers.',
            ],
            function () {
                Route::resource('contact', CarrierContactsController::class)->only([
                    'create',
                    'store',
                    'edit',
                    'update',
                    'destroy',
                ]);
            }
        );

        Route::resource('carriers', CarriersController::class);

        // Settings
        Route::group(
            [
                'prefix'    => 'settings',
                'as'        => 'settings.',
            ],
            function () {
                Route::get('/', [DashboardSettingController::class, 'index'])->name('index');

                Route::resource('ftp-folders', FTPFoldersController::class)->only([
                    'index',
                    'update',
                ]);

                Route::resource('division-districts', DivisionDistrictsController::class)->only([
                    'index',
                    'create',
                    'store',
                    'edit',
                    'update',
                    'destroy',
                ]);

                Route::resource('circuit-descriptions', CircuitDescriptionsController::class)->only([
                    'index',
                    'create',
                    'store',
                    'edit',
                    'update',
                ]);

                Route::resource('service-types', ServiceTypesController::class)->only([
                    'index',
                    'create',
                    'store',
                    'edit',
                    'update',
                    'destroy',
                ]);

                Route::resource('features', FeaturesController::class)->only([
                    'index',
                    'create',
                    'store',
                    'edit',
                    'update',
                    'destroy',
                ]);

                Route::post('/favorite-reports/order', [FavoriteReportsController::class, 'order'])->name('favorite-reports.order');
                Route::resource('favorite-reports', FavoriteReportsController::class)->only([
                    'index',
                    'destroy',
                ]);

                Route::resource('file-upload', FileUploadsController::class)->only([
                    'index',
                    'store',
                ]);
            }
        );


        // Users
        Route::group(
            [
                'prefix' => '/users',
                'as'     => 'users.',
            ],
            function () {

                Route::get('/history/{id}', [UsersController::class, 'history'])->name('history');
                Route::get('/session/{id}/{from}', [UsersController::class, 'session'])->name('session');
                Route::get('/resetPasswordEmail/{id}', [UsersController::class, 'resetPasswordEmail'])->name('resetPasswordEmail');
            }
        );

        Route::resource('users', UsersController::class)->only([
            'index',
            'create',
            'store',
            'edit',
            'update',
            'destroy',
            'show',
        ]);
    }
);
