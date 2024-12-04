<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class TmpImportVillage extends Model
{
      
      protected $fillable=['id'];
      public $timestamps=false;

      public function States()
      {
             return $this->hasOne('App\Model\State','id','state_id');
      }
      public function Districts()
      {
             return $this->hasOne('App\Model\District','id','district_id');
      }
      public function Blocks()
      {
             return $this->hasOne('App\Model\BlocksMc','id','block_id');
      }
      public function Villages()
      {
             return $this->hasOne('App\Model\Village','id','village_id');
      }
}
