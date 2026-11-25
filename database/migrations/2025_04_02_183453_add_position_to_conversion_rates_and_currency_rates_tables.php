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
        // Колонки могли бути додані раніше без запису в migrations
        if (! Schema::hasColumn('conversion_rates', 'position')) {
            Schema::table('conversion_rates', function (Blueprint $table) {
                $table->integer('position')->default(0)->after('id');
            });
        }

        if (! Schema::hasColumn('currency_rates', 'position')) {
            Schema::table('currency_rates', function (Blueprint $table) {
                $table->integer('position')->default(0)->after('id');
            });
        }
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
