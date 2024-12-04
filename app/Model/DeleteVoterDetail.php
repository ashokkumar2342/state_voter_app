<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class DeleteVoterDetail extends Model
{
      protected $fillable=['id'];
      public $timestamps=false;
      protected $table='delete_voter_details';

      
}
