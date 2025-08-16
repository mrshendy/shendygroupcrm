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
    Schema::table('leave_balances', function (Blueprint $table) {
        $table->integer('annual_days')->default(8);   // رصيد سنوي
        $table->integer('casual_days')->default(4);   // عارضة
    });
}

    public function down()
    {
        Schema::table('leave_balances', function (Blueprint $table) {
            //
        });
    }
};
