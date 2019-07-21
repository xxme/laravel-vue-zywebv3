<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Event;
use App\EventDetail;
use App\Comment;
use App\Type;
use App\User;
use App\ProductList;
use App\Offer;
use App\Expense;
use App\Holidays;
use App\Deposit;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($ym)
    {
        $init['events'] = Event::where('event_date', 'LIKE', "$ym%")
            ->orderBy('event_date', 'ASC')
            ->orderBy('status', 'ASC')
            ->orderBy('apm', 'ASC')
            ->orderBy('id', 'ASC')
            ->with(['comments' => function ($query) {
                $query->with('user');
            }, 'expense' => function ($query) {
                $query->with('user');
            }, 'user', 'details', 'productlist', 'deposit'])->get();
        $types = Type::get(['id', 'name']);
        foreach($types as $type) {
            $typeArray[$type->id] = $type->name;
        }
        foreach($init['events'] as $key=>$event) {
            $event['typenames'] = $this->getTypeNames($event->types, $typeArray);
            if($event->comments) {
                $event['comments'] = json_decode($event->comments, true);
            }
            if($event->total) {
                $event['totalname'] = $this->getTypeNames($event->total, $typeArray);
            }
            if($event->details) {
                if($event->details->aboutgoods) {
                    $event['goods'] = $this->getTypeNames($event->details->aboutgoods, $typeArray);
                }
                if($event->details->carefully) {
                    $event['carefulnames'] = $this->getTypeNames($event->details->carefully, $typeArray);
                }
                if($event->details->trucks) {
                    $event['trucks'] = $this->getTypeNames($event->details->trucks, $typeArray);
                }
                if($event->details->images) {
                    $event['images'] = json_decode($event->details->images, true);
                }
            }
            // 应收金额计算
            if($event->deposit) {
                $event['receivable'] = $event->amount - $event->deposit->jpy;
            } else {
                $event['receivable'] = $event->amount;
            }
        }

        $init['auth'] = auth()->user();
        $init['users'] = User::where('group_id', '<', 3)->get(['id', 'name', 'profileimg']);
        $init['holidays'] = $this->getHolidays($ym);
        
        return $init;
    }

    public function item($id)
    {
        $event = Event::with(['comments' => function ($query) {
                $query->with('user');
            }, 'expense' => function ($query) {
                $query->with('user');
            }, 'user', 'details', 'productlist', 'deposit'])->find($id);
        $types = Type::get(['id', 'name']);
        foreach($types as $type) {
            $typeArray[$type->id] = $type->name;
        }
        $event['typenames'] = $this->getTypeNames($event->types, $typeArray);
        if($event->comments) {
            $event['comments'] = json_decode($event->comments, true);
        }
        if($event->total) {
            $event['totalname'] = $this->getTypeNames($event->total, $typeArray);
        }
        if($event->details) {
            if($event->details->aboutgoods) {
                $event['goods'] = $this->getTypeNames($event->details->aboutgoods, $typeArray);
            }
            if($event->details->carefully) {
                $event['carefulnames'] = $this->getTypeNames($event->details->carefully, $typeArray);
            }
            if($event->details->trucks) {
                $event['trucks'] = $this->getTypeNames($event->details->trucks, $typeArray);
            }
            if($event->details->images) {
                $event['images'] = json_decode($event->details->images, true);
            }
        }
        // 应收金额计算
        if($event->deposit) {
            $event['receivable'] = $event->amount - $event->deposit->jpy;
        } else {
            $event['receivable'] = $event->amount;
        }
        $init['events'] = $event;
        $init['auth'] = auth()->user();
        $init['users'] = User::where('group_id', '<', 3)->get(['id', 'name', 'profileimg']);
        $init['holidays'] = $this->getHolidays($event->event_date);
        
        return $init;
    }

    private function getHolidays($ym) {
        $lastDateOfHoliday = Holidays::orderBy('date', 'desc')->first();
        $syncFlag = false;
        if($lastDateOfHoliday) {
            $dt = Carbon::today();
            $dt2 = new Carbon($lastDateOfHoliday['date']);
            $dt->addMonth();
            if($dt->gt($dt2)) {
                $syncFlag = true;
            }
        }
        
        if(!$lastDateOfHoliday || $syncFlag) {
            // get holidays
            $holidays = $this->syncHolidays();
        } else {
            $holidays = Holidays::where('date', 'LIKE', "$ym%")->get(['date', 'title']);
        }
        return $holidays;
    }

    private function syncHolidays() {
        $content = file_get_contents("https://holidays-jp.github.io/api/v1/date.json");
        $holidays = array();
        if($content) {
            $holidays = json_decode($content, true);
            foreach($holidays as $key => $holiday) {
                $hasData = Holidays::where('date', $key)->first();
                if(!$hasData) {
                    $obj = new Holidays();
                    $obj->date = $key;
                    $obj->title = $holiday;
                    $obj->save();
                }
            }
        }
        return $holidays;
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
        $objEvent->product_list_id = $inputs['product_list_id'];
        $objEvent->order_id = $inputs['order_id'];
        $objEvent->partner = $inputs['partner'];
        $objEvent->amount = $inputs['amount'];
        $objEvent->total = json_encode($inputs['total'], JSON_UNESCAPED_UNICODE);
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
            $objEventDetail->images = json_encode(array_merge($inputs['filethumbs'], $inputs['productlistimgs']), JSON_UNESCAPED_UNICODE);
            $objEventDetail->aboutgoods = json_encode($inputs['aboutgoods'], JSON_UNESCAPED_UNICODE);
            $objEventDetail->carefully = json_encode($inputs['careful'], JSON_UNESCAPED_UNICODE);
            $objEventDetail->from_address = $inputs['from']['address'];
            $objEventDetail->from_elevator = $inputs['from']['elevator'];
            $objEventDetail->from_floor = $inputs['from']['floors'];
            $objEventDetail->from_btype = $inputs['from']['btype'];
            $objEventDetail->to_address = $inputs['to']['address'];
            $objEventDetail->to_elevator = $inputs['to']['elevator'];
            $objEventDetail->to_floor = $inputs['to']['floors'];
            $objEventDetail->to_btype = $inputs['to']['btype'];
            $objEventDetail->phone = preg_replace('/ー|-|（|）|(|)/', '', mb_convert_kana($inputs['phone'], "n"));
            $objEventDetail->wechat = $inputs['wechat'];
            $objEventDetail->save();
            if(!empty($inputs['comment'])){
                $objComment = new Comment();
                $objComment->user_id = $user_id;
                $objComment->event_id = $event_id;
                $objComment->content = $inputs['comment'];
                \AdminLog::saveWithLog($objComment, config('const.log_comment'), config('const.log_action_add'));
            }
            if($inputs['product_list_id']) {
                $objProductList = ProductList::find($inputs['product_list_id']);
                $objProductList->event_id = $event_id;
                $objProductList->save();
            }
            if($inputs['order_id']) {
                $objOffer = Offer::find($inputs['order_id']);
                $objOffer->event_id = $event_id;
                $objOffer->save();
            }
            if($inputs['deposit_jpy'] > 0 || $inputs['deposit_rmb'] > 0) {
                $objDeposit = new Deposit();
                $objDeposit->event_id = $event_id;
                $objDeposit->jpy = $inputs['deposit_jpy'];
                $objDeposit->rmb = $inputs['deposit_rmb'];
                $objDeposit->save();
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
        return Event::with(['details', 'deposit'])->findOrFail($id);
    }

    public function showbycontract($id)
    {
        $event = Event::with(['details', 'deposit'])->findOrFail($id);
        $types = Type::get(['id', 'name']);
        foreach($types as $type) {
            $typeArray[$type->id] = $type->name;
        }
        $event['typenames'] = $this->getTypeNames($event->types, $typeArray);
        if($event->comments) {
            $event['comments'] = json_decode($event->comments, true);
        }
        if($event->total) {
            $event['totalname'] = $typeArray[$event->total];
        }
        if($event->details) {
            if($event->details->aboutgoods) {
                $event['goods'] = $this->getTypeNames($event->details->aboutgoods, $typeArray);
            }
            if($event->details->carefully) {
                $event['carefulnames'] = $this->getTypeNames($event->details->carefully, $typeArray);
            }
            if($event->details->trucks) {
                $event['trucks'] = $this->getTypeNames($event->details->trucks, $typeArray);
            }
            if($event->details->images) {
                $event['images'] = json_decode($event->details->images, true);
            }
        }
        return response()->json($event); 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('admin.dashboard');
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
        $objEvent = Event::with(['details'])->findOrFail($id);
        if($objEvent->product_list_id && (!$inputs['product_list_id'] || $objEvent->product_list_id != $inputs['product_list_id'])){
            $objList = ProductList::find($objEvent->product_list_id);
            if($objList) {
                $objList->event_id = null;
                $objList->save();
            }
        }
        $objEvent->product_list_id = $inputs['product_list_id'];
        $objEvent->partner = $inputs['partner'];
        $objEvent->amount = $inputs['amount'];
        $objEvent->total = json_encode($inputs['total'], JSON_UNESCAPED_UNICODE);
        $objEvent->event_date = $inputs['eventdate'];
        $objEvent->apm = $inputs['apm'];
        $objEvent->start_time = $inputs['from']['time'];
        $objEvent->end_time = $inputs['to']['time'];
        $objEvent->types = json_encode($inputs['worktype'], JSON_UNESCAPED_UNICODE);
        $event_id = \AdminLog::saveWithLog($objEvent, config('const.log_event'), config('const.log_action_update'));

        if($event_id) {
            $objEventDetail = EventDetail::findOrFail($objEvent->details->id);
            $objEventDetail->event_id = $event_id;
            $objEventDetail->trucks = json_encode($inputs['truck'], JSON_UNESCAPED_UNICODE);
            $objEventDetail->images = json_encode(array_merge($inputs['filethumbs'], $inputs['productlistimgs']), JSON_UNESCAPED_UNICODE);
            $objEventDetail->aboutgoods = json_encode($inputs['aboutgoods'], JSON_UNESCAPED_UNICODE);
            $objEventDetail->carefully = json_encode($inputs['careful'], JSON_UNESCAPED_UNICODE);
            $objEventDetail->from_address = $inputs['from']['address'];
            $objEventDetail->from_elevator = $inputs['from']['elevator'];
            $objEventDetail->from_floor = $inputs['from']['floors'];
            $objEventDetail->from_btype = $inputs['from']['btype'];
            $objEventDetail->to_address = $inputs['to']['address'];
            $objEventDetail->to_elevator = $inputs['to']['elevator'];
            $objEventDetail->to_floor = $inputs['to']['floors'];
            $objEventDetail->to_btype = $inputs['to']['btype'];
            $objEventDetail->phone = preg_replace('/ー|-|（|）|(|)/', '', mb_convert_kana($inputs['phone'], "n"));
            $objEventDetail->wechat = $inputs['wechat'];
            $objEventDetail->save();
            if(!empty($inputs['comment'])){
                $objComment = new Comment();
                $objComment->user_id = auth()->user()->id;
                $objComment->event_id = $event_id;
                $objComment->content = $inputs['comment'];
                \AdminLog::saveWithLog($objComment, config('const.log_comment'), config('const.log_action_add'));
            }
            if($inputs['product_list_id']) {
                $objList = ProductList::find($inputs['product_list_id']);
                $objList->event_id = $event_id;
                $objList->save();
            }
            if($inputs['deposit_jpy'] > 0 || $inputs['deposit_rmb'] > 0) {
                $objDeposit = Deposit::where('event_id', $event_id)->first();
                if(!$objDeposit) {
                    $objDeposit = new Deposit();
                }
                $objDeposit->event_id = $event_id;
                $objDeposit->jpy = $inputs['deposit_jpy'];
                $objDeposit->rmb = $inputs['deposit_rmb'];
                $objDeposit->save();
            }
        }
        $event = Event::with(['comments' => function ($query) {
                $query->with('user');
            }, 'expense' => function ($query) {
                $query->with('user');
            }, 'user', 'details', 'productlist'])->findOrFail($id);
        $types = Type::get(['id', 'name']);
        foreach($types as $type) {
            $typeArray[$type->id] = $type->name;
        }
        $event['typenames'] = $this->getTypeNames($event->types, $typeArray);
        if($event->comments) {
            $event['comments'] = json_decode($event->comments, true);
        }
        if($event->total) {
            $event['totalname'] = $this->getTypeNames($event->total, $typeArray);
        }
        if($event->details) {
            if($event->details->aboutgoods) {
                $event['goods'] = $this->getTypeNames($event->details->aboutgoods, $typeArray);
            }
            if($event->details->carefully) {
                $event['carefulnames'] = $this->getTypeNames($event->details->carefully, $typeArray);
            }
            if($event->details->trucks) {
                $event['trucks'] = $this->getTypeNames($event->details->trucks, $typeArray);
            }
            if($event->details->images) {
                $event['images'] = json_decode($event->details->images, true);
            }
        }

        return response()->json($event);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $obj = Event::findOrFail($id);
        if($obj->product_list_id) {
            $objList = ProductList::findOrFail($obj->product_list_id);
            if($objList) {
                \AdminLog::saveLog($obj->product_list_id, config('const.log_shopping_list'), config('const.log_action_del'), __('messages.totalprice').' ￥'.number_format($objList->price));
                ProductList::destroy($obj->product_list_id);
            }
        }
        if($obj->order_id) {
            $objOffer = Offer::findOrFail($obj->order_id);
            if($objOffer) {
                \AdminLog::saveLog($obj->order_id, config('const.log_estimates'), config('const.log_action_del'));
                Offer::destroy($obj->order_id);
            }
        }
        \AdminLog::saveLog($id, config('const.log_event'), config('const.log_action_del'), $obj->event_date);
        return Event::destroy($id);
    }

    public function complete(Request $request) {
        $inputs = $request->all();
        $objEvent = Event::findOrFail($inputs['eventid']);
        $objExpense = Expense::where('event_id', $inputs['eventid'])->first();
        if(!$objExpense) {
            $objExpense = new Expense();
            $action = config('const.log_action_add');
        } else {
            $action = config('const.log_action_update');
        }
        $objExpense->event_id = $inputs['eventid'];
        $objExpense->user_id = $inputs['payee'];
        $objExpense->finalprice = $inputs['price'] > 0 ? $inputs['price'] : 0;
        $objExpense->zctingche = $inputs['tingche'] > 0 ? $inputs['tingche'] : 0;
        $objExpense->zccanyin = $inputs['canyin'] > 0 ? $inputs['canyin'] : 0;
        $objExpense->zcgaosu = $inputs['gaosu'] > 0 ? $inputs['gaosu'] : 0;
        $objExpense->zcjiayou = $inputs['jiayou'] > 0 ? $inputs['jiayou'] : 0;
        $objExpense->zcmaihuo = $inputs['maihuo'] > 0 ? $inputs['maihuo'] : 0;
        $objExpense->zcother = $inputs['other'] > 0 ? $inputs['other'] : 0;
        $objExpense->fxrmb = $inputs['rmb'] > 0 ? $inputs['rmb'] : 0;
        $objExpense->fxjpy = $inputs['jpy'] > 0 ? $inputs['jpy'] : 0;
        $objExpense->expenditure = 
                $objExpense->zctingche + $objExpense->zccanyin + 
                $objExpense->zcgaosu + $objExpense->zcjiayou + 
                $objExpense->zcmaihuo + $objExpense->zcother + 
                $objExpense->fxjpy;
        if($inputs['cause']) {
            $objExpense->cause = $inputs['cause'];
        }
        
        $objExpense->status = $inputs['sta'];
        \AdminLog::saveWithLog($objExpense, config('const.log_event_complete'), $action);
        if($objExpense->id && $objEvent->status == 1){
            $objEvent->status = 2;
            $objEvent->save();
        }

        return Expense::with(['user'])->find($objExpense->id);
    }

    // $type 1 入金していない 2 入金済み
    public function finances($type) {
        $auth = auth()->user();
        if($auth->group_id != 1) {
            return "403";
        }
        
        if($type == 1) {
            //未入金
            $finances['data'] = Expense::where('finalprice', '>', 0)->where('status', $type)->orderBy('created_at', 'DESC')->with(['user'])->paginate(20);
            $userprice = Expense::where('finalprice', '>', 0)->where('status', $type)
            ->groupBy('user_id')
            ->selectRaw('sum(finalprice) as sum, user_id')
            ->pluck('sum','user_id');
            
            $user = array();
            foreach($userprice as $key=>$up) {
                $uname = User::find($key,['name']);
                $user[$uname['name']] = $up;
            }
            if($user) {
                $finances['user'] = $user;
            }
        } else {
            //已入金
            $finances['data'] = Event::orderBy('event_date', 'DESC')->whereHas('expense', function ($query) use ($type) {
                $query->where('finalprice', '>', 0)->where('status', $type);
            })->orWhereHas('deposit', function ($query) use ($type) {
                $query->where('jpy', '>', 0);
            })->with(['expense' => function($query) {
                $query->with(['user']);
            }, 'deposit'])->paginate(20);
        }

        return $finances;
    }

    //入金操作
    public function setReceived(Request $request) {
        $ids = $request->all();
        $finances = Expense::whereIn('id', $ids)->update(['status' => 2]);
        return $finances;
    }

    /**
     * Batch attendance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function batchAttendance(Request $request) {
        $inputs = $request->all();
        $datefrom = new Carbon($inputs['eventdatefrom']);
        $dateto = new Carbon($inputs['eventdateto']);
        if($dateto->diffInDays($datefrom) < 0 || $dateto->diffInDays($datefrom) > 365) {
            $return['status'] = 'error';
            $return['message'] = __('messages.perioderror');
            return response()->json($return);
        }
        $weekNameArray = ['星期日', '星期一', '星期二', '星期三', '星期四', '星期五', '星期六'];
        $weekNames = array();
        foreach($inputs['checkedWeek'] as $weekid) {
            $weekNames[] = $weekNameArray[$weekid];
        }
        $weekNameString = implode(",", $weekNames);
        $logtype = "";
        if($inputs['attendance'] == 1) {
            $logtype = config('const.log_action_attendance');
        } else {
            $logtype = config('const.log_action_vacation');
        }
        $count = 0;
        $auth_id = auth()->user()->id;
        $type = $inputs['attendance'] == 1 ? '["19"]' : '["122"]';
        while($datefrom <= $dateto) {
            if(in_array($datefrom->dayOfWeek, $inputs['checkedWeek'])) {
                foreach($inputs['partner'] as $userid) {
                    $event_date = $datefrom->format('Y-m-d');
                    $obj = Event::where('types', $type)->where('user_id', $userid)->where('apm', $inputs['apm'])->where('event_date', $event_date)->first();
                    if(!$obj) {
                        $objEvent = new Event();
                        $objEvent->user_id = $userid;
                        $objEvent->event_date = $event_date;
                        $objEvent->apm = $inputs['apm'];
                        $objEvent->types = $type;
                        $objEvent->total = '[]';
                        if($objEvent->save()) {
                            $count ++;
                            $objEventDetail = new EventDetail();
                            $objEventDetail->event_id = $objEvent->id;
                            $objEventDetail->trucks = '[]';
                            $objEventDetail->images = '[]';
                            $objEventDetail->aboutgoods = '[]';
                            $objEventDetail->carefully = '[]';
                            $objEventDetail->save();
                            if(!empty($inputs['comment'])){
                                $objComment = new Comment();
                                $objComment->user_id = $auth_id;
                                $objComment->event_id = $objEvent->id;
                                $objComment->content = $inputs['comment'];
                                $objComment->save();
                            }
                        }
                    }
                }
            }
            $datefrom->addDay();
        }
        if($count > 0) {
            \AdminLog::saveLog($auth_id, config('const.log_event_attendance'), $logtype, $inputs['eventdatefrom'].'〜'.$inputs['eventdateto'].' '.$weekNameString);
            $return['status'] = 'success';
            return response()->json($return);
        } else {
            $return['status'] = 'error';
            $return['message'] = __('messages.noeligibledateserror');
            return response()->json($return);
        }
    }

    // public function exps() {
    //     $rs = array();
    //     // $finances = Expense::where('finalprice', '>', 0)->with(['event' => function ($query) {
    //     //     $query->with('deposit');
    //     // }])->get();
    //     $date = Carbon::create(2018, 05, 31, 24);
    //     $finances = Event::where('status', 2)->where('event_date', '<=', $date)->with(['expense' => function ($query) {
    //         $query->where('finalprice', '>', 0);
    //     }, 'deposit' => function ($query) {
    //         $query->where('jpy', '>', 0);
    //     }])->orderBy('event_date', 'ASC')->get();
    //     $types = Type::get(['id', 'name']);
    //     foreach($types as $type) {
    //         $typeArray[$type->id] = $type->name;
    //     }
    //     $html = '';
    //     foreach($finances as $k=>$value) {
    //         $rs[$k]['money'] = 0;
    //         // $rs[$k]['id'] = $value->id;
    //         // $rs[$k]['event_id'] = $value->event_id;
    //         if($value->expense) {
    //             $rs[$k]['money'] += $value->expense->finalprice;
    //         }
            
    //         if($value['deposit']) {
    //             $rs[$k]['money'] += $value->deposit->jpy;
    //         }
    //         if($rs[$k]['money'] == 0) {
    //             unset($rs[$k]);
    //         } else {
    //             $rs[$k]['date'] = $value->event_date;
    //             $rs[$k]['typenames'] = implode(',', $this->getTypeNames($value->types, $typeArray));
    //         }
    //     }
    //     $data['rs'] = $rs;
    //     return view('admin.others.test', $data);
    // }
}
