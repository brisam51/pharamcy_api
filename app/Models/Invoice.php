<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        "pharamcy_id",
        "company_id",
        "invoice_number",
        "delivery_date",
        "amount",
        "due_date",
        "discriotion",
        "'photo",
        "status",
    ];
}
