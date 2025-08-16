<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
       Schema::create('salaries', function (Blueprint $table) {
    $table->id();
    $table->foreignId('employee_id')->constrained()->onDelete('cascade');
    $table->string('month');
    $table->decimal('basic_salary', 10, 2)->nullable(); // 👈 ضيف هنا
    $table->decimal('allowances', 10, 2)->default(0);
    $table->decimal('deductions', 10, 2)->default(0);
    $table->decimal('net_salary', 10, 2)->default(0);
    $table->string('status')->default('pending');
    $table->text('notes')->nullable();
    $table->timestamps();
});

    }

    public function down(): void
    {
        Schema::dropIfExists('salaries');
    }
};
