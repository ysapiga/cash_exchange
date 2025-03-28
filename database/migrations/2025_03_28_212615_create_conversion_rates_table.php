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
        Schema::create('conversion_rates', function (Blueprint $table) {
            $table->id();
            $table->string('currency_from', 3);
            $table->string('currency_to', 3);
            $table->decimal('conversion_rate', 10, 4);
            $table->timestamps();

            $table->foreign('currency_from')
                ->references('currency_code')
                ->on('currencies')
                ->onDelete('cascade');

            $table->foreign('currency_to')
                ->references('currency_code')
                ->on('currencies')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversion_rates');
    }
};
