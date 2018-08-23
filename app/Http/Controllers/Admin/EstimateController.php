<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Offer;

// 見積
class EstimateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.dashboard');
    }

    /**
     * Return the specified resource.
     *
     * @param  int  $type 1.All 2.Untreated 3.Treated
     * @return \Illuminate\Http\Response
     */
    public function getlists($type) {
        // get holidays
        $ch = curl_init();
        $timeout = 5;
        curl_setopt ($ch, CURLOPT_URL, 'https://holidays-jp.github.io/api/v1/date.json');
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $init['holidays'] = json_decode(curl_exec($ch), true);
        curl_close($ch);
        if($type == 1) {
            $init['offers'] = Offer::with(['user', 'event'])->orderBy('updated_at', 'DESC')->paginate(20);
        } else if($type == 2) {
            $init['offers'] = Offer::with(['user', 'event'])->where('user_id', null)->orderBy('updated_at', 'DESC')->paginate(20);
        } else {
            $init['offers'] = Offer::with(['user', 'event'])->where('user_id', auth()->user()->id)->orderBy('updated_at', 'DESC')->paginate(20);
        }
        foreach($init['offers'] as $key=>$offer) {
            $offer['from'] = json_decode($offer->from_info, true);
            $offer['to'] = json_decode($offer->to_info, true);
            $offer['items'] = json_decode($offer->items, true);
            $offer['otherservice'] = json_decode($offer->other_service, true);
            $offer['images'] = json_decode($offer->images, true);
        }
        return $init;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $offer = Offer::findOrFail($id);
        $offer->from_info = json_decode($offer->from_info, true);
        $offer->to_info = json_decode($offer->to_info, true);
        // $offer['items'] = json_decode($offer->items, true);
        $offer->other_service = json_decode($offer->other_service, true);
        $offer->images = json_decode($offer->images, true);
        return $offer;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $inputs = $request->all();
        $objOffer = Offer::findOrFail($id);
        if(!$objOffer->user_id) {
            $objOffer->user_id = auth()->user()->id;
        }
        $images = array();
        if($objOffer->images) {
            $images = json_decode($objOffer->images, true);
        }
        if(array_key_exists('thumbs', $inputs)) {
            // 更新记录信息 保存新上传的照片
            $images = array_merge($inputs['thumbs'], $images);
            // var_dump($images);
            $objOffer->images = json_encode($images, JSON_UNESCAPED_UNICODE);
            if($inputs['note']) {
                $objOffer->manager_note = $inputs['note'];
            }
        } else {
            // 删除单张照片
            if(array_key_exists('img', $inputs) && $images) {
                $images = array_values(array_diff($images, array($inputs['img'])));
                $objOffer->images = json_encode($images, JSON_UNESCAPED_UNICODE);
            }
        }
        
        \AdminLog::saveWithLog($objOffer, config('const.log_estimates'), config('const.log_action_update'));

        $objOffer->from = json_decode($objOffer->from_info, true);
        $objOffer->to = json_decode($objOffer->to_info, true);
        $objOffer->items = json_decode($objOffer->items, true);
        $objOffer->otherservice = json_decode($objOffer->other_service, true);
        $objOffer->images = $images;

        return $objOffer;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
