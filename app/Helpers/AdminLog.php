<?php

namespace App\Helpers;
use Request;
use App\AdminLog as LogModel;
use App\Comment;

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
            if($type == config('const.log_event')) {
                $log['content'] = $obj->event_date;
            } else if($type == config('const.log_comment')) {
                $objcomment = Comment::with(['event'])->findOrFail($obj->id);
                $log['content'] = $objcomment->event->event_date.' '.__('messages.event').' #'.$objcomment->event->id.' '.$obj->content;
            }

            LogModel::create($log);
            return $obj->id;
        }
        return false;
    }

    public static function saveLog($objid, $type, $log_type, $content = null) {
        $logObj = new LogModel();
        $logObj['obj_id'] = $objid;
        $logObj['type'] = $type;
        $logObj['log_type'] = $log_type;
        $details['url'] = Request::fullUrl();
        $details['method'] = Request::method();
        $details['ip'] = Request::ip();
        $details['agent'] = Request::header('user-agent');
        $logObj['user_id'] = auth()->user()->id;
        $logObj['details'] = json_encode($details);
        if($content) {
            $logObj['content'] = $content;
        }

        $logObj->save();
    }
}