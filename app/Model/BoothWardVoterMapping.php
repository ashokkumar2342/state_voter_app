<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class BoothWardVoterMapping extends Model
{
      
      protected $fillable=['id'];
      protected $table='booth_ward_voter_mapping';
      public $timestamps=false;

       
      public function Districts()
      {
             return $this->hasOne('App\Model\District','id','district_id');
      }
       
}