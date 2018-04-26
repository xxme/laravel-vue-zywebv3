<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdminLog extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'obj_id', 'type', 'log_type', 'content', 'details'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
