<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Bank extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'branch',
        'code',
        'address',
        'phone',
        'email',
        'status'
    ];

   

    public function accounts()
    {
        return $this->hasMany(Account::class);
    }
}
