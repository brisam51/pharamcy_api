<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cheque_invoice', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cheque_id')->constrained('cheques')->onDelete('cascade'); // Foreign key to cheques table
            $table->foreignId('invoice_id')->constrained('invoices')->onDelete('cascade'); // Foreign key to invoices table
            $table->decimal('amount', 15, 2); // Amount paid by the cheque
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cheque_invoice');
    }
};
