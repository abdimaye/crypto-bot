<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Worker extends Model
{
    protected $guarded = [];

    public function trades()
    {
    	return $this->hasMany('App\Trade');
    }

    public static function isActive($id)
    {
    	return self::where('id', $id)->where('active', 1)->firstOrFail();
    }
}
