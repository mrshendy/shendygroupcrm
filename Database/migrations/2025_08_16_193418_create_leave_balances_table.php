<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leave_balances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->year('year'); // سنة الرصيد
            $table->integer('total_days')->default(28); // الرصيد الكلي
            $table->integer('used_days')->default(0);   // الأيام المستخدمة
            $table->integer('remaining_days')->default(28); // الأيام المتبقية
            $table->timestamps();

            $table->foreign('employee_id')->references('id')->on('employee')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leave_balances');
    }
};
