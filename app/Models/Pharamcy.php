<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;

class Pharamcy extends Model
{
    use HasFactory;
    protected $table="pharamcies";
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

    protected static function booted()
{
    static::addGlobalScope('user', function (Builder $builder) {
        if (\Illuminate\Support\Facades\Auth::check()) {
            $user = \Illuminate\Support\Facades\Auth::user();

            // Check if user has "superadmin" role (fix typo)
            if (!$user->hasRole('supperadmin')) {
                $builder->where('user_id', $user->id);
            }
        }
    });
}

}//end class
