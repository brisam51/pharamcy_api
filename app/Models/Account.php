<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Account extends Model
{
    use HasFactory;
    protected $fillable = [
        'account_number',
        'account_name',
        'bank_id',
        'user_id',
        'branch_name',
        'account_type',
        'balance',
        'status'
    ];

    public function cheques()
    {
        return $this->hasMany(Cheque::class);
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    
    
}
