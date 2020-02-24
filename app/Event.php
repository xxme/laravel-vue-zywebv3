<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use SoftDeletes;
    
    public function comments()
    {
        return $this->hasMany('App\Comment');
    }
    public function user()
    {
        return $this->belongsTo('App\User');
    }
    public function details()
    {
        return $this->hasOne('App\EventDetail');
    }
    // 收支管理，最终收入也用于入金管理
    public function expense()
    {
        return $this->hasOne('App\Expense');
    }
    public function productlist(){
        return $this->hasOne('App\ProductList');
    }
    //定金 押金，定金也用于入金管理
    public function deposit()
    {
        return $this->hasOne('App\Deposit');
    }
}
