<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('shifts', function (Blueprint $table) {
            $table->id(); // bigint unsigned auto_increment primary key
            $table->string('name');
            $table->json('days'); // JSON field مع تحقق تلقائي من Laravel
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('leave_allowance')->default(0);
            $table->timestamps(); // created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shifts');
    }
};
