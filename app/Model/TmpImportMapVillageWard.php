<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class TmpImportMapVillageWard extends Model
{
      
      protected $fillable=['id'];
      protected $table='tmp_import_map_village_ward';
      public $timestamps=false;

       
      public function Districts()
      {
             return $this->hasOne('App\Model\District','id','district_id');
      }
       
}