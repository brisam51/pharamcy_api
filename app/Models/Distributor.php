<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Distributor extends Model
{
    use HasFactory;
    protected $fillable=[
        "name",
        "economic_code",
        "contact_number",
        "email",
        "phone",
        "address",
        "national_id",
        "business_type",
        "description",
    ];

    public function invoices(){
        return $this->hasMany(Invoice::class);
    }
    
}
