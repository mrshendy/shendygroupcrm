<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // لو الجدول موجود، ما تعملش Create تاني
        if (!Schema::hasTable('shifts')) {
            Schema::create('shifts', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->json('days');
                $table->time('start_time');
                $table->time('end_time');
                $table->integer('leave_allowance')->default(0);
                $table->timestamps();
            });
        } else {
            // (اختياري) لو محتاج تتأكد من عمود معيّن
            Schema::table('shifts', function (Blueprint $table) {
                if (!Schema::hasColumn('shifts', 'leave_allowance')) {
                    $table->integer('leave_allowance')->default(0);
                }
            });
        }
    }

    public function down(): void
    {
      
    }
};
