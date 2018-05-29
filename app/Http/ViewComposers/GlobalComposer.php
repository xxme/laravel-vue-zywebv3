<?php

namespace App\Http\ViewComposers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use App\Offer;
use App\ProductList;

class GlobalComposer {

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('currentUser', Auth::user());
        $view->with('countoffers', Offer::whereNull('user_id')->count());
        $view->with('countproductlist', ProductList::whereNull('event_id')->count());
    }

}