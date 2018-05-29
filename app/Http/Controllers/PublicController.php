<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\OfferApplied;
use App\Offer;
use App\User;

class PublicController extends Controller
{
    public function public() {
        return view('vue');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function createOffer(Request $request) {
        $inputs = $request->all();
        $objOffer = new Offer();
        $objOffer->nickname = $inputs['s1']['name'];
        $objOffer->apm = $inputs['s1']['apm'];
        $objOffer->hopedate = $inputs['s1']['hopedate'];
        $objOffer->tel = $inputs['s1']['tel'];
        $objOffer->partner = $inputs['partner'];

        $frominfo = array();
        if($inputs['s1']['fromadr1']) {
            $fromadr = $inputs['s1']['fromadr1'] + $inputs['s1']['fromadr2'];
        } else {
            $fromadr = $inputs['s1']['fromadr2'];
        }
        $frominfo['adr'] = $fromadr;
        $frominfo['elevator'] = $inputs['s1']['fdt'];
        if($inputs['s1']['flc']) {
            $frominfo['floor'] = $inputs['s1']['flc'];
        }
        if($inputs['s1']['fbtype']) {
            $frominfo['fbtype'] = $inputs['s1']['fbtype'];
        }
        $objOffer->from_info = json_encode($frominfo, JSON_UNESCAPED_UNICODE);

        $toinfo = array();
        $toinfo['adr'] = $inputs['s1']['toadr'];
        $toinfo['elevator'] = $inputs['s1']['tdt'];
        if($inputs['s1']['tlc']) {
            $toinfo['floor'] = $inputs['s1']['tlc'];
        }
        if($inputs['s1']['tbtype']) {
            $toinfo['tbtype'] = $inputs['s1']['tbtype'];
        }
        $objOffer->to_info = json_encode($toinfo, JSON_UNESCAPED_UNICODE);
        $objOffer->items = json_encode($inputs['s2'], JSON_UNESCAPED_UNICODE);
        $objOffer->other_service = json_encode($inputs['s3'], JSON_UNESCAPED_UNICODE);
        $objOffer->save();

        if($objOffer->id) {
            $managerEmailList = User::where('group_id', '<', 3)->pluck('email')->toArray();
            Mail::to($managerEmailList)->send(new OfferApplied($objOffer));
        }

        return $objOffer;
    }
}
