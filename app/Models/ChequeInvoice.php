<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class ChequeInvoice extends Pivot
{
    
    use HasFactory;

   protected $table = 'cheque_invoice';

    protected $fillable = [
        'cheque_id',
        'invoice_id',
        'amount',
    ];

    public function cheque()
    {
        return $this->belongsTo(Cheque::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
