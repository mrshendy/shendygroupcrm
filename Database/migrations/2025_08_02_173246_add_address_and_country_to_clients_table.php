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
    Schema::table('clients', function (Blueprint $table) {
        
        if (!Schema::hasColumn('clients', 'address')) {
            $table->string('address')->nullable();
        }

   
        if (!Schema::hasColumn('clients', 'country_id')) {
            $table->foreignId('country_id')->nullable()->constrained('countries')->onDelete('set null');
        }
    });
}

public function down(): void
{
    Schema::table('clients', function (Blueprint $table) {
        $table->dropColumn(['address', 'country_id']);
    });
}
};
