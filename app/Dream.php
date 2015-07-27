<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Dream extends Model {

    /**
     * Added attribute
     *
     * @var array
     */
    protected $appends = ['is_owner'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['created_at', 'updated_at', 'user_id'];

    /**
     * The belongsTo relation.
     * 
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * is_owner mutator.
     * 
     */
    public function getIsOwnerAttribute()
    {
        if(Auth::check())
        {
            return $this->attributes['user_id'] === Auth::id() || Auth::user()->admin;
        }
        
        return false;
    }

}
