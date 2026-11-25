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
        // Таблиця могла бути створена раніше без запису в migrations
        if (Schema::hasTable('exchange_points')) {
            return;
        }

        Schema::create('exchange_points', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('address');
            $table->string('coordinates');
            $table->string('telephone');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exchange_points');
    }
};
