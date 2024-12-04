<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class WardPanchayat extends Model
{
      
      protected $fillable=['id'];
      protected $table='ward_ps';
      public $timestamps=false;

       
      public function States()
      {
      	 return $this->hasOne('App\Model\State','id','states_id');
      }
      public function Districts()
      {
      	 return $this->hasOne('App\Model\District','id','districts_id');
      }
      public function Blocks()
      {
             return $this->hasOne('App\Model\BlocksMc','id','blocks_id');
      }
       
}