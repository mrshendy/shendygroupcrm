<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('shifts', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // اسم الشفت
            $table->json('days');   // الأيام (مثلا [ "saturday","sunday","monday" ])
            $table->time('start_time'); // بداية الشفت
            $table->time('end_time');   // نهاية الشفت
            $table->integer('leave_allowance')->default(0); // عدد أيام الإجازة المسموح بها
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('shifts');
    }
};
