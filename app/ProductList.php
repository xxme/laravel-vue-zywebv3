<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductList extends Model
{
    use SoftDeletes;
    
    public function user()
    {
        return $this->belongsTo('App\User');
    }
    public function event()
    {
        return $this->belongsTo('App\Event');
    }
    public function items()
    {
        return $this->hasMany('App\ProductItem');
    }
}
