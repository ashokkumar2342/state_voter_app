<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class TmpImportBlock extends Model
{
      
      protected $fillable=['id']; 
      public $timestamps=false;

       
      public function Districts()
      {
             return $this->hasOne('App\Model\District','id','district_id');
      }
       
}