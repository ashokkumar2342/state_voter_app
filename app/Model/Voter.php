<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Voter extends Model
{
      protected $fillable=['id','sr_no','assembly_id','assembly_part_id'];
      public $timestamps=false;

      public function states()
      {
      	 return $this->hasOne('App\Model\State','id','states_id');
      }
      public function district()
      {
      	 return $this->hasOne('App\Model\District','id','districts_id');
      }
      public function blockMCS()
      {
             return $this->hasOne('App\Model\BlocksMc','id','blocks_id');
      }
}
