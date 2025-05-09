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
        Schema::create('cheques', function (Blueprint $table) {
            $table->id();
            $table->string('cheque_number')->unique();
            $table->date('issue_date');
            $table->date('due_date');
            $table->decimal('amount', 15, 2);
            $table->string('payee_name');
            $table->enum('status', ['cleared', 'pending', 'returned'])->default('pending'); // pending, cleared, bounced
            $table->enum('payment_type', ['invoice_payment', 'salary_payment', 'expanses'])->default('invoice_payment'); // personal or business cheque
            $table->string('reference_number')->nullable(); // reference number for tracking
            $table->text('description')->nullable();
            $table->string('attachment')->nullable(); // path to the cheque image or PDF
            $table->boolean('is_void')->default(false); // to mark if the cheque is voided  
          $table->foreignId('user_id')->constrained()->onDelete('cascade'); // user who created the cheque
           $table->foreignId('account_id')->constrained()->onDelete('cascade'); // bank account associated with the cheque
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cheques');
    }
};
