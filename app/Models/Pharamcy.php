<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Pharamcy extends Model
{
    use HasFactory;
    protected $fillable = [
        "name",
        "national_id",
        "address",
        "phone",
        "email",
        "logo",
        "subscription_start_date",
        "subscription_end_date",
        "status",
        "user_id",
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function invoices(){
        return $this->hasMany(Invoice::class);
    }
}//end class
