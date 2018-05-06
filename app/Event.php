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
    public function expense()
    {
        return $this->hasOne('App\Expense');
    }
    public function productlist(){
        return $this->hasOne('App\ProductList');
    }
}
