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
     * @param  int  $type 1 add 2 update 3 delete(soft) 4 comment
     * @param  string  $details
     * @return true or false
     */
    public static function saveWithLog($obj, $type) {
        if($obj->save()) {
            $log = [];
            $log['obj_id'] = $obj->id;
            $log['type'] = $type;
            $details['url'] = Request::fullUrl();
            $details['method'] = Request::method();
            $details['ip'] = Request::ip();
            $details['agent'] = Request::header('user-agent');
            $log['user_id'] = auth()->user()->id;
            $log['details'] = json_encode($details);

            LogModel::create($log);
            return true;
        }
        return false;
    }
}