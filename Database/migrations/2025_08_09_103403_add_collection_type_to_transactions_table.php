<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // أضف العمود والعلاقة إذا غير موجودين
            if (! Schema::hasColumn('transactions', 'client_id')) {
                $table->foreignId('client_id')
                    ->nullable()
                    ->constrained('clients')   // عدّل الاسم لو جدولك مختلف
                    ->cascadeOnUpdate()
                    ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // احذف القيد والعمود إذا كانا موجودين
            if (Schema::hasColumn('transactions', 'client_id')) {
                // يحذف الـ FK ثم العمود
                $table->dropConstrainedForeignId('client_id');
            }
        });
    }
};
