<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PollingDayTime extends Model
{
      
      protected $fillable=['id'];
      protected $table='polling_day_time';
      public $timestamps=false;

       
      public function BlocksMc()
      {
             return $this->hasOne('App\Model\BlocksMc','id','block_id');
      }
       
}