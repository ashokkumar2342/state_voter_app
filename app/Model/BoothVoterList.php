<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class BoothVoterList extends Model
{
      
      protected $fillable=['id'];
      protected $table='booth_voter_list';
      public $timestamps=false;
}