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
    Schema::table('offers', function (Blueprint $table) {
        $table->text('notes')->nullable();
        $table->date('contract_date')->nullable();
        $table->date('waiting_date')->nullable();
        $table->string('contract_file')->nullable();
        $table->text('close_reason')->nullable();
        $table->text('reject_reason')->nullable();
    });
}

public function down()
{
    Schema::table('offers', function (Blueprint $table) {
        $table->dropColumn(['notes', 'contract_date', 'waiting_date', 'contract_file', 'close_reason', 'reject_reason']);
    });
}

};
