<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Assembly extends Model
{
      protected $fillable=['id'];
      public $timestamps=false;
      protected $table='assemblys';

      public function states()
      {
      	 return $this->hasOne('App\Model\State','id','states_id');
      }
      public function district()
      {
      	 return $this->hasOne('App\Model\District','id','district_id');
      }
}
