<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Comment;

class CommentController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $inputs = $request->all();
        $objComment = new Comment();
        $objComment->user_id = auth()->user()->id;
        $objComment->event_id = $inputs['event_id'];
        $objComment->content = $inputs['content'];
        $commnet_id = \AdminLog::saveWithLog($objComment, config('const.log_comment'), config('const.log_action_add'));
        
        return Comment::with(['user'])->findOrFail($commnet_id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $obj = Comment::with(['event'])->findOrFail($id);
        \AdminLog::saveLog($id, config('const.log_comment'), config('const.log_action_del'), 
                $obj->event->event_date.' '.__('messages.event').' #'.$obj->event->id.' '.$obj->content);
        return Comment::destroy($id);
    }
}
