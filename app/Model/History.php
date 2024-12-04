<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
      protected $fillable=['id','table_name','database_name'];
      public $timestamps=false;
      protected $table='historys';

       
}
