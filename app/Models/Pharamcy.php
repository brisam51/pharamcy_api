<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pharamcy extends Model
{
   protected $fillable=[
    "name","national_id","address","subscription_start_date","subscription_end_date","status","user_id",
   ];
}
