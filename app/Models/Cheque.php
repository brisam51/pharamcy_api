<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Cheque extends Model
{
    use HasFactory;
    protected $fillable = [
        'cheque_number',
        'issue_date',
        'due_date',
        'amount',
        'payee_name',
        'status',
        'payment_type',
        'reference_number',
        'description',
        'attachment',
        'is_void',
        'user_id',
        'account_id',
    ];
    public function invoices()
    {
        return $this->belongsToMany(Invoice::class, 'cheque_invoice')
            ->withPivot('amount')
            ->withTimestamps();
    }
    

public function accounts()
    {
        return $this->belongsTo(Account::class);
    }

public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function pharamcy()
    {
        return $this->belongsTo(Pharamcy::class);
    }

    public function distributor()
    {
        return $this->belongsTo(Distributor::class);
    }


}//end class
