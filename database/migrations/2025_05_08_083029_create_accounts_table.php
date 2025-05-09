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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('account_number')->unique(); // Unique account number    
            $table->string('account_name'); // Name of the account holder
            $table->string('branch_name')->nullable(); // Branch name
            $table->enum('account_type',['saving','current'])->default('current'); // Type of account (e.g., savings, current)
            $table->float('balance')->nullable(); // Account balance
            $table->enum('status', ['active', 'inactive'])->default('active'); // Status of the account (active/inactive)
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Foreign key to users table
            $table->foreignId('bank_id')->constrained()->onDelete('cascade'); // Foreign key to users table for created_by
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
