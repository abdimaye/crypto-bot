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
}
