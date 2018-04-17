<?php

namespace App\Helpers;
use Request;
use App\AdminLog as LogModel;

class AdminLog
{
    /**
     * Admin log.
     *
     * @param  int  $user_id
     * @param  model  $obj
     * @param  int  $type 1 event 2 type 3 comment 4 user 5 Event commission 6 Event complete
     *                    7 shopping list 8 Estimates 9 Repetition
     * @param  int  $log_type 1 add 2 update 3 delete(soft)
     * @param  string  $details
     * @return $obj->id or false
     */
    public static function saveWithLog($obj, $type, $log_type) {
        if($obj->save()) {
            $log = [];
            $log['obj_id'] = $obj->id;
            $log['type'] = $type;
            $log['log_type'] = $log_type;
            $details['url'] = Request::fullUrl();
            $details['method'] = Request::method();
            $details['ip'] = Request::ip();
            $details['agent'] = Request::header('user-agent');
            $log['user_id'] = auth()->user()->id;
            $log['details'] = json_encode($details);

            LogModel::create($log);
            return $obj->id;
        }
        return false;
    }
}