<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\Factory as ViewFactory;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(ViewFactory $view)
    {
        Schema::defaultStringLength(191);
        $view->composer('*', 'App\Http\ViewComposers\GlobalComposer');
        if (Schema::hasTable('type_groups')){
            $typegroups = DB::table('type_groups')->where('status', 1)->get();

            View::share('typegroups', $typegroups);
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
