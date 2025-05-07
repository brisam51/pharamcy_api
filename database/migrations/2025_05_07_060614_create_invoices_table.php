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
            $table->foreignId('company_id')->constrained('distribution_companies');
            $table->string('invoice_number')->unique();
            $table->date('delivery_date');
            $table->decimal('amount',10,2);
            $table->date('due_date');
            $table->text('discriotion')->nullable();
            $table->text('photo')->nullable();
            $table->enum('status',['paid','open','due','unmatured'])->nullable();
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
