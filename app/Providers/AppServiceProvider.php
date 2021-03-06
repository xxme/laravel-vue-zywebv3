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
        // get typegroups for menu
        if (Schema::hasTable('type_groups')){
            $typegroups = DB::table('type_groups')->whereNull('deleted_at')->get();

            View::share('typegroups', $typegroups);
        }
        // get usergroups for menu
        if (Schema::hasTable('user_groups')){
            $usergroups = DB::table('user_groups')->where('id', '>', 1)->get();
            View::share('usergroups', $usergroups);
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
