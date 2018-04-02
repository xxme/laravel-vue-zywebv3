<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TypeGroup extends Model
{
    public function types()
    {
        return $this->hasMany('App\Type', 'group_id');
    }
}
