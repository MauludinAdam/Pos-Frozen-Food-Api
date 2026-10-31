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
        Schema::create('stock_opnames', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('code')->unique();
            $table->foreignId('product_id')->constrained('products')->restrictOnDelete();
            $table->integer('stock_system');
            $table->integer('stock_physical');
            $table->integer('difference');
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete();
            $table->longText('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_opnames');
    }
};
