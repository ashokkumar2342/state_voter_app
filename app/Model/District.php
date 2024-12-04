<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
      protected $fillable=['id'];
      public $timestamps=false;

      public function states()
      {
      	 return $this->hasOne('App\Model\State','id','state_id');
      }
}
