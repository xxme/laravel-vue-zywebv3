<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Event;
use App\EventDetail;
use App\Comment;
use App\Type;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($ym)
    {
        $init['events'] = Event::whereNull('deleted_at')
            ->where('event_date', 'LIKE', "$ym%")
            ->orderBy('event_date', 'ASC')->orderBy('id', 'ASC')
            ->with(['comments' => function ($query) {
                $query->with('user');
            }, 'user', 'details'])->get();
        $types = Type::get(['id', 'name']);
        foreach($types as $type) {
            $typeArray[$type->id] = $type->name;
        }
        foreach($init['events'] as $key=>$event) {
            $event['images'] = json_decode($event->details->images, true);
            $event['comments'] = json_decode($event->comments, true);
            $event['goods'] = $this->getTypeNames($event->details->aboutgoods, $typeArray);
            $event['totalname'] = $typeArray[$event->total];
            $event['typenames'] = $this->getTypeNames($event->types, $typeArray);
            $event['carefulnames'] = $this->getTypeNames($event->details->carefully, $typeArray);
        }
        $init['auth'] = auth()->user();
        
        return $init;
    }

    private function getTypeNames($ids, $typeArray, $decode = true) {
        if($decode) {
            $ids = json_decode($ids, true);
        }
        $nameArray = array();
        foreach($ids as $id) {
            $nameArray[] = $typeArray[$id];
        }
        return $nameArray;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($date = null)
    {
        $data['pageTitle'] = __('messages.events');
        $data['subTitle'] = __('messages.eventsSubTitle');
        return view('admin.dashboard', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $inputs = $request->all();
        $objEvent = new Event();
        $user_id = auth()->user()->id;
        $objEvent->user_id = $user_id;
        $objEvent->shopping_id = $inputs['shoppingid'];
        $objEvent->partner_id = $inputs['partner'];
        $objEvent->amount = $inputs['amount'];
        $objEvent->total = $inputs['total'];
        $objEvent->event_date = $inputs['eventdate'];
        $objEvent->apm = $inputs['apm'];
        $objEvent->start_time = $inputs['from']['time'];
        $objEvent->end_time = $inputs['to']['time'];
        $objEvent->types = json_encode($inputs['worktype'], JSON_UNESCAPED_UNICODE);

        $event_id = \AdminLog::saveWithLog($objEvent, config('const.log_event'), config('const.log_action_add'));

        if($event_id) {
            $objEventDetail = new EventDetail();
            $objEventDetail->event_id = $event_id;
            $objEventDetail->trucks = json_encode($inputs['truck'], JSON_UNESCAPED_UNICODE);
            $objEventDetail->images = json_encode($inputs['filethumbs'], JSON_UNESCAPED_UNICODE);
            $objEventDetail->aboutgoods = json_encode($inputs['aboutgoods'], JSON_UNESCAPED_UNICODE);
            $objEventDetail->carefully = json_encode($inputs['careful'], JSON_UNESCAPED_UNICODE);
            $objEventDetail->from_address = $inputs['from']['address'];
            $objEventDetail->from_elevator = $inputs['from']['elevator'];
            $objEventDetail->from_floor = $inputs['from']['floors'];
            $objEventDetail->to_address = $inputs['to']['address'];
            $objEventDetail->to_elevator = $inputs['to']['elevator'];
            $objEventDetail->to_floor = $inputs['to']['floors'];
            $objEventDetail->phone = $inputs['phone'];
            $objEventDetail->wechat = $inputs['wechat'];
            $objEventDetail->save();
            if(!empty($inputs['comment'])){
                $objComment = new Comment();
                $objComment->user_id = $user_id;
                $objComment->event_id = $event_id;
                $objComment->content = $inputs['comment'];
                \AdminLog::saveWithLog($objComment, config('const.log_comment'), config('const.log_action_add'));
            }
        }
        return response()->json($objEvent);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        //
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
