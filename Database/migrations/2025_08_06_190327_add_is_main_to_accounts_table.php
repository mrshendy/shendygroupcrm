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
    Schema::table('accounts', function (Blueprint $table) {
       $table->string('bank')->nullable();
$table->boolean('is_main')->default(false);
    });
}

public function down(): void
{
    Schema::table('accounts', function (Blueprint $table) {
        $table->dropColumn('is_main');
    });
}


};
