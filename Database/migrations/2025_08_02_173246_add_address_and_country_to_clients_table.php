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

public function down()
{
    Schema::table('clients', function (Blueprint $table) {
        if (Schema::hasColumn('clients', 'country_id')) {
            // احذف الـ foreign key حسب الاسم التلقائي
            $table->dropForeign('clients_country_id_foreign');
            $table->dropColumn('country_id');
        }

        if (Schema::hasColumn('clients', 'address')) {
            $table->dropColumn('address');
        }
    });
}


};
