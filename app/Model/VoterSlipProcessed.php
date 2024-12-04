<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class VoterSlipProcessed extends Model
{
      protected $fillable=['id'];
      public $timestamps=false;
      protected $table='voter_slip_processed';

      public function states()
      {
      	 return $this->hasOne('App\Model\State','id','state_id');
      }
      public function district()
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
      public function WardVillages()
      {
             return $this->hasOne('App\Model\WardVillage','id','ward_id');
      }
      public function booths()
      {
             return $this->hasOne('App\Model\PollingBooth','id','booth_id');
      }
}
