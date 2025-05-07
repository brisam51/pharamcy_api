<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DistributionCompany extends Model
{
    protected $fillable=[
        "name","economic_code","contact_number","address","national_id","business_type","description",
    ];
}
