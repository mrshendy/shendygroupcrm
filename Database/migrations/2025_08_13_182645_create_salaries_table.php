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

            // علاقة مع جدول employees
            $table->foreignId('employee_id')
                ->constrained('employees')   // 👈 حدد الجدول بشكل صريح
                ->onDelete('cascade');

            // الشهر (مثلاً 2025-08)
            $table->string('month', 7);

            // الراتب الأساسي
            $table->decimal('basic_salary', 10, 2)->default(0);

            // البدلات
            $table->decimal('allowances', 10, 2)->default(0);

            // الخصومات
            $table->decimal('deductions', 10, 2)->default(0);

            // الصافي
            $table->decimal('net_salary', 10, 2)->default(0);

            // الحالة
            $table->enum('status', ['pending', 'paid'])->default('pending');

            // ملاحظات
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('salaries');
    }
};
