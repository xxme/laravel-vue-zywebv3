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
use App\IncomeView;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($ym)
    {
        $user = auth()->user();
        $detailswhere = [
            ['carefully', 'LIKE', '%']
        ];
        if($user->group_id == 6) {
            $user->group_id = null;
            $detailswhere = [
                ['carefully', 'NOT LIKE', '%"130"%']
            ];
        }

        $whereData = [
            ['event_date', 'LIKE', "$ym%"]
        ];
        $ymd = explode("-", $ym);
        $fromto = explode("|", $ym);
        if(count($fromto) > 1){
            //From - To
            $whereData = [
                ['event_date', '>=', "$fromto[0]"],
                ['event_date', '<=', "$fromto[1]"]
            ];
        } else if (count($ymd) > 2) {
            $whereData = [
                ['event_date', '>=', "$ym"]
            ];
        }

        $init['events'] = Event::where($whereData)
        ->whereHas('details', function ($query) use ($detailswhere) {
            $query->where($detailswhere);
        })
        ->orderBy('event_date', 'DESC')
        // ->orderBy('status', 'ASC')
        ->orderBy('apm', 'ASC')
        // ->orderBy('id', 'ASC')
        ->with(['comments' => function ($query) {
            $query->with('user');
        }, 'expense' => function ($query) {
            $query->with('user');
        }, 'user', 'details', 'productlist', 'deposit'])->get();
        $typeArray = $this->getTypeArray();
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

        $init['auth'] = $user;
        $init['users'] = User::where('group_id', '<', 3)->orWhere('group_id', '=', 6)
            ->get(['id', 'name', 'profileimg']);
        $init['holidays'] = $this->getHolidays($ym);
        
        return $init;
    }

    /**
     * get events with area.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request) {
        $fromto = "";
        if ($request->from) {
            $fromto = $request->from;
        }
        if ($request->to) {
            $fromto .= "|" . $request->to;
        }
        $objEvent = $this->index($fromto);

        return response()->json($objEvent);
    }

    public function getsales() {
        $dt = Carbon::today();
        $whereData = [
            ['event_date', '>=', $dt->subYear()->year.'-01-01'],
            ['event_date', '<=', $dt->addYear()->year.'-12-31']
        ];
        $user = auth()->user();
        $detailswhere = [
            ['carefully', 'LIKE', '%']
        ];
        if($user->group_id == 6) {
            $detailswhere = [
                ['carefully', 'NOT LIKE', '%"130"%']
            ];
        }
        $events = IncomeView::where($whereData)->where($detailswhere)->get();

        // $events = Event::where($whereData)
        // ->whereHas('details', function ($query) use ($detailswhere) {
        //     $query->where($detailswhere);
        // })
        // ->whereHas('expense')
        // ->orderBy('event_date', 'ASC')
        // ->with(['deposit', 'expense'])->get();
        $sales['lastyear'][1] = 
        $sales['lastyear'][2] = 
        $sales['lastyear'][3] = 
        $sales['lastyear'][4] = 
        $sales['lastyear'][5] = 
        $sales['lastyear'][6] = 
        $sales['lastyear'][7] = 
        $sales['lastyear'][8] = 
        $sales['lastyear'][9] = 
        $sales['lastyear'][10] = 
        $sales['lastyear'][11] = 
        $sales['lastyear'][12] = 
        $sales['thisyear'][1] = 
        $sales['thisyear'][2] = 
        $sales['thisyear'][3] = 
        $sales['thisyear'][4] = 
        $sales['thisyear'][5] = 
        $sales['thisyear'][6] = 
        $sales['thisyear'][7] = 
        $sales['thisyear'][8] = 
        $sales['thisyear'][9] = 
        $sales['thisyear'][10] = 
        $sales['thisyear'][11] = 
        $sales['thisyear'][12] = 0;
        foreach($events as $key=>$event) {
            $dt = Carbon::today();
            $eventdt = new Carbon($event->event_date);
            // 报价
            $amount = $event->amount;
            // 定金
            $jpy = $event->jpy;
            // 最后收款
            $finalprice = $event->finalprice;
            // 年度Flag
            $yearFlag = "thisyear";
            if ($eventdt->year == $dt->subYear()->year) {
                $yearFlag = "lastyear";
            }
            if ($finalprice == 0) {
                // 未完成或没有最终收钱的日程
                if ($amount > $jpy) {
                    $sales[$yearFlag][$eventdt->month] += $amount;
                } else {
                    $sales[$yearFlag][$eventdt->month] += $jpy;
                }
            } else {
                $sales[$yearFlag][$eventdt->month] += $finalprice + $jpy;
            }
            //定金
            // if ($event->deposit && $event->deposit->jpy > 0) {
            //     $dt = Carbon::today();
            //     //去年
            //     if ($eventdt->year == $dt->subYear()->year) {
            //         $sales['lastyear'][$eventdt->month] += $event->deposit->jpy;
            //     } else {
            //         $sales['thisyear'][$eventdt->month] += $event->deposit->jpy;
            //     }
            // }

            // //最终收款
            // if ($event->expense && $event->expense->finalprice > 0) {
            //     $dt = Carbon::today();
            //     //去年
            //     if ($eventdt->year == $dt->subYear()->year) {
            //         $sales['lastyear'][$eventdt->month] += $event->expense->finalprice;
            //     } else {
            //         // echo $eventdt->month;
            //         $sales['thisyear'][$eventdt->month] += $event->expense->finalprice;
            //     }
            // }
        }
        return response()->json($sales);
    }

    public function item($id)
    {
        $event = Event::with(['comments' => function ($query) {
                $query->with('user');
            }, 'expense' => function ($query) {
                $query->with('user');
            }, 'user', 'details', 'productlist', 'deposit'])->find($id);
        $typeArray = $this->getTypeArray();
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
        \DB::beginTransaction();
        try {
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
            // 3为支出任务，sataus除了3以外，全部为普通未完成任务
            $objEvent->status = $inputs['status'] == 3 ? 3 : 1;
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
                    if ($inputs['deposit_jpy'] > 0) {
                        $objDeposit->receipted_at = $inputs['receipted_at'];
                        $objDeposit->deposit_type = $inputs['deposit_type'];
                    }
                    $objDeposit->save();
                }

                // 创建支出Expense
                if ($objEvent->status == 3) {
                    $typeArray = $this->getTypeArray();
                    $action = config('const.log_action_add');
                    $objExpense = new Expense();
                    $objExpense->event_id = $event_id;
                    $objExpense->user_id = $user_id;
                    $objExpense->expenditure = $objEvent->amount;
                    $objExpense->cause = implode(",", $this->getTypeNames($objEvent->types, $typeArray));
                    \AdminLog::saveWithLog($objExpense, config('const.log_event_complete'), $action);
                }
            }
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
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
        $typeArray = $this->getTypeArray();
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
        \DB::beginTransaction();
        try {
            $objEvent = Event::with(['details'])->findOrFail($id);
            if($objEvent->product_list_id && (!$inputs['product_list_id'] || $objEvent->product_list_id != $inputs['product_list_id'])){
                $objList = ProductList::find($objEvent->product_list_id);
                if($objList) {
                    $objList->event_id = null;
                    $objList->save();
                }
            }

            $updatedcontent = array();
            // 获取全部类型信息，id转名称使用
            $typeArray = $this->getTypeArray();
            // 记录日程内容变化
            if ($objEvent->product_list_id != $inputs['product_list_id']) {
                $updatedcontent[config('const.product_list_id')] = $objEvent->product_list_id.' ==> '.$inputs['product_list_id'];
            }
            if ($objEvent->partner != $inputs['partner']) {
                $updatedcontent[config('const.partner')] = $objEvent->partner.' ==> '.$inputs['partner'];
            }
            if ($objEvent->amount != $inputs['amount']) {
                $updatedcontent[config('const.amount')] = $objEvent->amount.' ==> '.$inputs['amount'];
            }
            if ($objEvent->total != json_encode($inputs['total'], JSON_UNESCAPED_UNICODE)) {
                $oldTotal = $this->getTypeNames($objEvent->total, $typeArray);
                $newTotal = $this->getTypeNames(json_encode($inputs['total'], JSON_UNESCAPED_UNICODE), $typeArray);
                $updatedcontent[config('const.total')] = implode(",", $oldTotal).' ==> '.implode(",", $newTotal);
            }
            if ($objEvent->event_date != $inputs['eventdate']) {
                $updatedcontent[config('const.event_date')] = $objEvent->event_date.' ==> '.$inputs['eventdate'];
            }
            if ($objEvent->apm != $inputs['apm']) {
                $updatedcontent[config('const.apm')] = ($objEvent->apm == null ? config('const.unset') : config('const.apm'.$objEvent->apm)).' ==> '.config('const.apm'.$inputs['apm']);
            }
            if ($objEvent->start_time != $inputs['from']['time']) {
                $updatedcontent[config('const.start_time')] = $objEvent->start_time.' ==> '.$inputs['from']['time'];
            }
            if ($objEvent->end_time != $inputs['to']['time']) {
                $updatedcontent[config('const.end_time')] = $objEvent->end_time.' ==> '.$inputs['to']['time'];
            }
            if ($objEvent->types != json_encode($inputs['worktype'], JSON_UNESCAPED_UNICODE)) {
                $oldTypes = $this->getTypeNames($objEvent->types, $typeArray);
                $newTypes = $this->getTypeNames(json_encode($inputs['worktype'], JSON_UNESCAPED_UNICODE), $typeArray);
                $updatedcontent[config('const.worktype')] = implode(",", $oldTypes).' ==> '.implode(",", $newTypes);
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

            if($objEvent->save()) {
                $objEventDetail = EventDetail::findOrFail($objEvent->details->id);
                $objEventDetail->event_id = $id;
                if ($objEventDetail->trucks != json_encode($inputs['truck'], JSON_UNESCAPED_UNICODE)) {
                    $oldTrucks = $this->getTypeNames($objEventDetail->trucks, $typeArray);
                    $newTrucks = $this->getTypeNames(json_encode($inputs['truck'], JSON_UNESCAPED_UNICODE), $typeArray);
                    $updatedcontent[config('const.trucks')] = implode(",", $oldTrucks).' ==> '.implode(",", $newTrucks);
                }
                if ($objEventDetail->aboutgoods != json_encode($inputs['aboutgoods'], JSON_UNESCAPED_UNICODE)) {
                    $oldAboutgoods = $this->getTypeNames($objEventDetail->aboutgoods, $typeArray);
                    $newAboutgoods = $this->getTypeNames(json_encode($inputs['aboutgoods'], JSON_UNESCAPED_UNICODE), $typeArray);
                    $updatedcontent[config('const.aboutgoods')] = implode(",", $oldAboutgoods).' ==> '.implode(",", $newAboutgoods);
                }
                if ($objEventDetail->carefully != json_encode($inputs['careful'], JSON_UNESCAPED_UNICODE)) {
                    $oldCareful = $this->getTypeNames($objEventDetail->carefully, $typeArray);
                    $newCareful = $this->getTypeNames(json_encode($inputs['careful'], JSON_UNESCAPED_UNICODE), $typeArray);
                    $updatedcontent[config('const.carefully')] = implode(",", $oldCareful).' ==> '.implode(",", $newCareful);
                }
                if ($objEventDetail->from_address != $inputs['from']['address']) {
                    $updatedcontent[config('const.from_address')] = $objEventDetail->from_address.' ==> '.$inputs['from']['address'];
                }
                if ($objEventDetail->from_elevator != $inputs['from']['elevator']) {
                    $updatedcontent[config('const.from_elevator')] = ($objEventDetail->from_elevator == null ? config('const.unset') : config('const.elevator'.$objEventDetail->from_elevator)).' ==> '.($inputs['from']['elevator'] == "0" ? config('const.unset') : config('const.elevator'.$inputs['from']['elevator']));
                }
                if ($objEventDetail->from_floor != $inputs['from']['floors']) {
                    $updatedcontent[config('const.from_floor')] = $objEventDetail->from_floor.' ==> '.$inputs['from']['floors'];
                }
                if ($objEventDetail->from_btype != $inputs['from']['btype']) {
                    $updatedcontent[config('const.from_btype')] = ($objEventDetail->from_btype == null ? config('const.unset') : config('const.btype'.$objEventDetail->from_btype)).' ==> '.($inputs['from']['btype'] == "0" ? config('const.unset') : config('const.btype'.$inputs['from']['btype']));
                }
                if ($objEventDetail->to_address != $inputs['to']['address']) {
                    $updatedcontent[config('const.to_address')] = $objEventDetail->to_address.' ==> '.$inputs['to']['address'];
                }
                if ($objEventDetail->to_elevator != $inputs['to']['elevator']) {
                    $updatedcontent[config('const.to_elevator')] = ($objEventDetail->to_elevator == null ? config('const.unset') : config('const.elevator'.$objEventDetail->to_elevator)).' ==> '.($inputs['to']['elevator'] == "0" ? config('const.unset') : config('const.elevator'.$inputs['to']['elevator']));
                }
                if ($objEventDetail->to_floor != $inputs['to']['floors']) {
                    $updatedcontent[config('const.to_floor')] = $objEventDetail->to_floor.' ==> '.$inputs['to']['floors'];
                }
                if ($objEventDetail->to_btype != $inputs['to']['btype']) {
                    $updatedcontent[config('const.to_btype')] = ($objEventDetail->to_btype == null ? config('const.unset') : config('const.btype'.$objEventDetail->to_btype)).' ==> '.($inputs['to']['btype'] == "0" ? config('const.unset') : config('const.btype'.$inputs['to']['btype']));
                }
                if ($objEventDetail->phone != preg_replace('/ー|-|（|）|(|)| |　/', '', mb_convert_kana($inputs['phone'], "n"))) {
                    $updatedcontent[config('const.phone')] = $objEventDetail->phone.' ==> '.preg_replace('/ー|-|（|）|(|)| |　/', '', mb_convert_kana($inputs['phone'], "n"));
                }
                if ($objEventDetail->wechat != $inputs['wechat']) {
                    $updatedcontent[config('const.wechat')] = $objEventDetail->wechat.' ==> '.$inputs['wechat'];
                }
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
                $objEventDetail->phone = preg_replace('/ー|-|（|）|(|)| |　/', '', mb_convert_kana($inputs['phone'], "n"));
                $objEventDetail->wechat = $inputs['wechat'];
                $objEventDetail->save();

                if(!empty($inputs['comment'])){
                    $objComment = new Comment();
                    $objComment->user_id = auth()->user()->id;
                    $objComment->event_id = $id;
                    $objComment->content = $inputs['comment'];
                    \AdminLog::saveWithLog($objComment, config('const.log_comment'), config('const.log_action_add'));
                }
                if($inputs['product_list_id']) {
                    $objList = ProductList::find($inputs['product_list_id']);
                    $objList->event_id = $id;
                    $objList->save();
                }
                if($inputs['deposit_jpy'] > 0 || $inputs['deposit_rmb'] > 0) {
                    $objDeposit = Deposit::where('event_id', $id)->first();
                    if(!$objDeposit) {
                        $objDeposit = new Deposit();
                    } else {
                        if ($objDeposit->jpy != $inputs['deposit_jpy']) {
                            $updatedcontent[config('const.deposit_jpy')] = $objDeposit->jpy.' ==> '.$inputs['deposit_jpy'];
                        }
                        if ($objDeposit->rmb != $inputs['deposit_rmb']) {
                            $updatedcontent[config('const.deposit_rmb')] = $objDeposit->rmb.' ==> '.$inputs['deposit_rmb'];
                        }
                        if ($inputs['deposit_jpy'] > 0) {
                            // 定金收款日期或类型被更改
                            if ($objDeposit->receipted_at != $inputs['receipted_at']) {
                                $updatedcontent[config('const.receipted_at')] = $objDeposit->receipted_at.' ==> '.$inputs['receipted_at'];
                            }
                            if ($objDeposit->type != $inputs['deposit_type']) {
                                $updatedcontent[config('const.deposit_type')] = ($objDeposit->type == null ? config('const.unset') : config('const.deposit_type'.$objDeposit->type)).' ==> '.config('const.deposit_type'.$inputs['deposit_type']);
                            }
                        }
                    }
                    $objDeposit->event_id = $id;
                    $objDeposit->jpy = $inputs['deposit_jpy'];
                    $objDeposit->rmb = $inputs['deposit_rmb'];
                    if ($inputs['deposit_jpy'] > 0) {
                        $objDeposit->receipted_at = $inputs['receipted_at'];
                        $objDeposit->type = $inputs['deposit_type'];
                    }
                    
                    $objDeposit->save();
                }
            }

            \AdminLog::saveLog($id, config('const.log_event'), config('const.log_action_update'), json_encode($updatedcontent, JSON_UNESCAPED_UNICODE));

            $event = Event::with(['comments' => function ($query) {
                    $query->with('user');
                }, 'expense' => function ($query) {
                    $query->with('user');
                }, 'user', 'details', 'productlist'])->findOrFail($id);
            
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
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
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
            //最终收款未入金
            $finances['data'] = Expense::with(['event', 'user'])->where('finalprice', '>', 0)->where('status', $type)->orderBy('created_at', 'DESC')->paginate(20);

            // $finances['data'] = Event::orderBy('event_date', 'DESC')->whereHas('expense', function ($query) use ($type) {
            //     $query->where('finalprice', '>', 0)->where('status', $type);
            // })->orWhereHas('deposit', function ($query) use ($type) {
            //     $query->whereNotNull('type')->where([
            //         ['jpy', '>', 0],
            //         ['type', '=', 1]
            //     ]);
            // })->with(['expense' => function($query) {
            //     $query->with(['user']);
            // }, 'deposit'])->paginate(20);

            $userprice = Expense::with(['event'])->where('finalprice', '>', 0)->where('status', $type)->get();
            // ->groupBy('user_id')
            // ->selectRaw('sum(finalprice) as sum, user_id')
            // ->pluck('sum','user_id');
            
            // $uname = User::find($key,['name']);
            $uname = User::get(['id', 'name']);
            $user = array();
            $userWithPrice = array();
            foreach ($uname as $value) {
                $user[$value->id] = $value->name;
            }
            foreach($userprice as $key=>$up) {
                if (!array_key_exists($user[$up->user_id], $userWithPrice)) {
                    $userWithPrice[$user[$up->user_id]] = 0;
                }
                if ($up->event->status == 3) {
                    $userWithPrice[$user[$up->user_id]] -= $up->expenditure;
                } else {
                    $userWithPrice[$user[$up->user_id]] += $up->finalprice;
                }
                // $uname = User::find($key,['name']);
                // $user[$uname['name']] = $up;
            }
            if($userWithPrice) {
                $finances['user'] = $userWithPrice;
            }
        } else if ($type == 2) {
            //最终收款已入金
            $finances['data'] = Event::orderBy('event_date', 'DESC')->whereHas('expense', function ($query) use ($type) {
                $query->where('finalprice', '>', 0)->where('status', $type);
            })->orWhereHas('deposit', function ($query) use ($type) {
                $query->where('jpy', '>', 0)->whereIn('type', [2, 3]);
            })->with(['expense' => function($query) {
                $query->with(['user']);
            }, 'deposit'])->paginate(20);
        } else if ($type == 3) {
            // 定金未入金List
            $finances['data'] = Deposit::with(['event'])->where([
                ['status', '=', 0],
                ['jpy', '>', 0],
                ['type', '=', 1]
            ])->orderBy('receipted_at', 'DESC')->paginate(20);

            // 定金未入金SUM
            $finances['sum'] = Deposit::with(['event'])->where([
                ['status', '=', 0],
                ['jpy', '>', 0],
                ['type', '=', 1]
            ])->sum('jpy');
        } else {
            // 定金已入金 type == 4
            $finances['data'] = Deposit::with(['event'])->where([
                ['status', '=', 1],
                ['jpy', '>', 0],
                ['type', '=', 1]
            ])->orderBy('receipted_at', 'DESC')->paginate(20);
        }

        return $finances;
    }

    //最终收款入金操作
    public function setReceived(Request $request) {
        $ids = $request->all();
        $return = Expense::whereIn('id', $ids)->update(['status' => 2]);
        return $return;
    }

    //定金(现金)入金操作
    public function setReceivedDeposit(Request $request) {
        $ids = $request->all();
        $return = Deposit::whereIn('id', $ids)->update(['status' => 1]);
        return $return;
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

    /**
     * 从view获取月间收入情报
     *
     * @return \Illuminate\Http\Response
     */
    public function getIncomeStatisics($ym = null) {
        $auth = auth()->user();
        if($auth->group_id != 1) {
            return "403";
        }
        if(!isset($ym)) {
            // 获取当月日志
            $dt = Carbon::now();
            $month = $dt->month < 10 ? '0'.$dt->month:$dt->month;
            $ym = $dt->year.'-'.$month;
        }
        $objEvent = IncomeView::where('event_date', 'LIKE', "$ym%")->get();

        return response()->json($objEvent);
    }

    /**
     * 从Type表获取全部类型的配列
     *
     * @return array 
     */
    private function getTypeArray() {
        $typeArray = array();
        $types = Type::get(['id', 'name']);
        foreach($types as $type) {
            $typeArray[$type->id] = $type->name;
        }
        return $typeArray;
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
