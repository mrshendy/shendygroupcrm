<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
     public function up(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->string('contact_name')->nullable();
            $table->string('job')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('contact_email')->nullable();
            $table->boolean('is_main_contact')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn(['contact_name', 'job', 'contact_phone', 'contact_email', 'is_main_contact']);
        });
    }
};
