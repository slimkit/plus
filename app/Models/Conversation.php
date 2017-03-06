<?php

namespace Zhiyi\Plus\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Conversation extends Model
{
    protected $fillable = ['type', 'user_id', 'content', 'options'];	

   	public function getCreatedAtAttribute($value)
   	{
   		$time = new Carbon($value);
      return $this->attributes['created_at'] = $time->timestamp;
    }

    public function getUpdatedAtAttribute($value)
    {
    	$time = new Carbon($value);
        return $this->attributes['updated_at'] = $time->timestamp;
    }
}
