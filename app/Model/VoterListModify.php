<?php

namespace App\Model;
use Illuminate\Database\Eloquent\Model;

class VoterListModify extends Model
{	
	 protected $fillable=['id'];
      public $timestamps=false;
     protected $table='voter_list_modify';
}
