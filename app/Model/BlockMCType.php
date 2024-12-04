<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class BlockMCType extends Model
{
      
      protected $fillable=['id'];
      protected $table='block_mc_type';
      public $timestamps=false;

       
      public function Districts()
      {
             return $this->hasOne('App\Model\District','id','district_id');
      }
       
}