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
    public function up(): void
{
    Schema::create('salaries', function (Blueprint $table) {
        $table->id();
        $table->foreignId('employee_id')->constrained()->onDelete('cascade');
        $table->string('month');
        $table->decimal('amount', 10, 2)->default(0);
        $table->decimal('allowances', 10, 2)->default(0);
        $table->decimal('deductions', 10, 2)->default(0);
        $table->decimal('net_salary', 10, 2)->default(0);
        $table->enum('status', ['pending', 'paid'])->default('pending');
        $table->text('notes')->nullable();
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
        Schema::dropIfExists('salaries');
    }
};
