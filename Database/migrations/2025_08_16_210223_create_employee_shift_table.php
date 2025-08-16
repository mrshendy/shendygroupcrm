<?php
// database/migrations/xxxx_xx_xx_create_employee_shift_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeShiftTable extends Migration
{
    public function up()
    {
        Schema::create('employee_shift', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->foreignId('shift_id')->constrained()->onDelete('cascade');
            $table->integer('custom_leave_allowance')->nullable(); // لو حابب تحدد رصيد مختلف لكل موظف
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('employee_shift');
    }
}
