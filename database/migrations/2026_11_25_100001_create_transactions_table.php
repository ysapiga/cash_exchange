<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->restrictOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); // оператор
            $table->foreignId('exchange_point_id')->nullable()->constrained()->nullOnDelete();

            $table->foreignId('from_currency_id')->constrained('currencies')->restrictOnDelete(); // що клієнт віддав
            $table->decimal('from_amount', 15, 2);
            $table->foreignId('to_currency_id')->constrained('currencies')->restrictOnDelete(); // що клієнт отримав
            $table->decimal('to_amount', 15, 2);
            $table->decimal('rate', 12, 4); // курс на момент операції

            $table->dateTime('performed_at');
            $table->text('note')->nullable();
            $table->timestamps();

            $table->index(['customer_id', 'performed_at']);
            $table->index('performed_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
