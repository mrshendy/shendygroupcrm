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
    Schema::create('attendances', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('employee_id')->nullable();
        $table->foreign('employee_id')->references('id')->on('employee')->onDelete('cascade');
        $table->timestamp('check_in')->nullable();   // وقت الحضور
        $table->timestamp('check_out')->nullable();  // وقت الانصراف
        $table->integer('hours')->nullable();        // عدد ساعات الحضور
        $table->date('attendance_date');
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
        Schema::dropIfExists('attendances');
    }
};
