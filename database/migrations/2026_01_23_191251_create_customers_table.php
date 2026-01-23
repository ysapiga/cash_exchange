<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id(); // первинний ключ
            $table->string('identifier')->unique(); // унікальний ідентифікатор
            $table->string('name');
            $table->string('last_name');
            $table->string('telephone')->nullable(); // nullable, якщо може бути порожнім
            $table->string('email')->unique();
            $table->date('date_of_birth')->nullable(); // nullable для необов'язкових дат
            $table->timestamps(); // created_at і updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
