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
        Schema::create('stock_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->enum('type', ['in', 'out']);
            $table->dateTime('date');
            $table->decimal('total_quantity', 18,4);
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('sender_name')->nullable();
            $table->string('receiver_name')->nullable();
            $table->string('phone')->nullable();
            $table->longText('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_transactions');
    }
};
