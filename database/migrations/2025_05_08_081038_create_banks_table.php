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
        Schema::create('banks', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Bank name
            $table->string('code')->unique(); // Bank code
            $table->string('branch')->nullable(); // Branch name
            $table->string('phone')->nullable(); // Phone number
            $table->string('email')->nullable(); // Email address
            $table->text('address')->nullable(); // SWIFT/BIC code for international transfers
           $table->enum('status',['active','inactive'])->default('active'); // Status of the bank (active/inactive)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banks');
    }
};
