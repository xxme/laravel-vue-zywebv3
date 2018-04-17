<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Event;
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
            ->with('comments', 'user')->get();
        foreach($init['events'] as $key=>$event) {
            $event['details'] = json_decode($event->details, true);
            if($event['details']['aboutgoods']) {
                $ids = implode(",", $event['details']['aboutgoods']);
                $aboutgoods = Type::where('id', 'IN', $ids)->get();
                foreach($aboutgoods as $goods) {
                    var_dump($goods);
                }
            }
        }
        $init['auth'] = auth()->user();
        
        return $init;
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
        $details = array(
            'aboutgoods' => $inputs['aboutgoods'],
            'careful' => $inputs['careful'],
            'total' => $inputs['total'],
            'from' => $inputs['from'],
            'to' => $inputs['to'],
            'phone' => $inputs['phone'],
            'wechat' => $inputs['wechat']
        );
        $user_id = auth()->user()->id;
        $objEvent->user_id = $user_id;
        $objEvent->shopping_id = $inputs['shoppingid'];
        $objEvent->agent_id = $inputs['partner'];
        $objEvent->amount = $inputs['amount'];
        $objEvent->event_date = $inputs['eventdate'];
        $objEvent->apm = $inputs['apm'];
        $objEvent->types = json_encode($inputs['worktype'], JSON_UNESCAPED_UNICODE);
        $objEvent->event_date = $inputs['eventdate'];
        $objEvent->truck_ids = json_encode($inputs['truck'], JSON_UNESCAPED_UNICODE);
        $objEvent->images = json_encode($inputs['filethumbs'], JSON_UNESCAPED_UNICODE);
        $objEvent->details = json_encode($details, JSON_UNESCAPED_UNICODE);

        $event_id = \AdminLog::saveWithLog($objEvent, config('const.log_event'), config('const.log_action_add'));

        if($event_id && !empty($inputs['comment'])) {
            $objComment = new Comment();
            $objComment->user_id = $user_id;
            $objComment->event_id = $event_id;
            $objComment->content = $inputs['comment'];
            \AdminLog::saveWithLog($objComment, config('const.log_comment'), config('const.log_action_add'));
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
