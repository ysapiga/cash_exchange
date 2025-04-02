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
        Schema::table('conversion_rates', function (Blueprint $table) {
            $table->integer('position')->default(0)->after('id');
        });

        Schema::table('currency_rates', function (Blueprint $table) {
            $table->integer('position')->default(0)->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('conversion_rates', function (Blueprint $table) {
            $table->dropColumn('position');
        });

        Schema::table('currency_rates', function (Blueprint $table) {
            $table->dropColumn('position');
        });
    }
};
