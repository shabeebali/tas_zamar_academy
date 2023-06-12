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
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->decimal('amount',10,2);
            $table->string('location')->nullable();
            $table->string('designation');
            $table->string('address')->nullable();
            $table->string('comment')->nullable();
            $table->string('payment_transaction_id')->nullable();
            $table->string('payment_status');
            $table->json('payment_meta')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};
