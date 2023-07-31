<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\Builder;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // use bootstrap for laravel pagination
        Paginator::useBootstrap();

        Model::$snakeAttributes = false;

        Blade::directive('notdefined', function ($expression) {
            $parts = explode(',', $expression);
            $variable = trim($parts[0]);

            $value = count($parts) > 1 ? trim($parts[1]) : $variable;
            $message = count($parts) > 2 ? trim($parts[2]) : '\'Not defined\'';

            return "<?php echo isset($variable) ? e($value) : '<i class=\"text-muted\">' . $message . '</i>' ?>";
        });

        Builder::macro('logQuery', function () {
            $this->getConnection()->listen(function ($query) {
                \Log::info(
                    $query->sql,
                    $query->bindings,
                    $query->time
                );
            });

            return $this;
        });
    }
}
