<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class UserBlockAssign extends Model
{
      protected $fillable=['id'];
      public $timestamps=false;

      public function Districts()
      {
             return $this->hasOne('App\Model\District','id','district_id');
      }
      public function Blocks()
      {
             return $this->hasOne('App\Model\BlocksMc','id','block_id');
      }
}
