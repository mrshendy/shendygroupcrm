<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('employees', function (Blueprint $table) {
    $table->id();
    $table->string('full_name');
    $table->string('employee_code')->unique();
    $table->string('email')->unique();
    $table->string('phone')->nullable();
    $table->string('job_title');
    $table->string('department')->nullable();
    $table->enum('employment_status', ['دائم', 'متعاقد', 'تحت التدريب']);
    $table->enum('employment_type', ['دوام كامل', 'دوام جزئي']);
    $table->decimal('salary', 10, 2)->nullable();
    $table->date('hiring_date')->nullable();
    $table->date('birth_date')->nullable();
    $table->enum('gender', ['ذكر', 'أنثى']);
    $table->text('address')->nullable();
    $table->text('notes')->nullable();
    $table->string('avatar')->nullable();
    $table->enum('status', ['مفعل', 'غير مفعل'])->default('مفعل');
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employees');
    }
};
