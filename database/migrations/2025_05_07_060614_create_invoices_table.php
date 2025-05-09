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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pharamcy_id')->constrained();
            $table->foreignId('distributor_id')->constrained();
            $table->string('invoice_number')->unique();
            $table->date('delivery_date');
            $table->decimal('total_amount', 15, 2);
            $table->decimal('paid_amount', 15, 2)->default(0);
            $table->decimal('outstanding_amount', 15, 2)->default(0);
            $table->date('due_date');
            $table->text('description')->nullable();
            $table->string('photo')->nullable();
            $table->enum('status', ['paid', 'open', 'due', 'unmatured'])->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
                       $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
