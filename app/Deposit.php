<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Deposit extends Model
{
    // 定金
    public function event()
    {
        return $this->belongsTo('App\Event');
    }
}
