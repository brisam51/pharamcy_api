<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Invoice extends Model
{
    use HasFactory;
    
    protected $fillable = [
        "pharamcy_id",
        "distributor_id",
        "invoice_number",
        "delivery_date",
        "total_amount",
        'paid_amount',
        'outstanding_amount',
        "due_date",
        "description",
        "'photo",
        "status",
        "user_id",
        "pharamcy_id",
        "distributor_id",
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function distributor(){
        return $this->belongsTo(Distributor::class);
    }

    public function pharamcy(){
        return $this->belongsTo(Pharamcy::class);
    }
    public function cheques(){
        return $this->belongsToMany(Cheque::class)->withPivot('amount')->withTimestamps();
    }
}
