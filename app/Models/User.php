<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Scopes\SuperAdminScope;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
     use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [

        'full_name',
        'national_id',
        'photo',
        'medical_council_id',
        'contract_number',
        'email',
        'role',//supperadmin,admin,user
        'status', //active,inactive
        'address',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function pharamcies()
    {
        return $this->hasMany(Pharamcy::class);
    }
    public function cheques()
    {
        return $this->hasMany(Cheque::class);
    }
    public function accounts()
    {
        return $this->hasMany(Account::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }


    // protected static function booted()
    // {
    //    parent::booted();
    //    static::addGlobalScope(new SuperAdminScope());
    // }
}//end class
 