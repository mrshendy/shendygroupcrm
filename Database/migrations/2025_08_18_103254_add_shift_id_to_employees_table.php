<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('employee', function (Blueprint $table) {
            // أضف العمود لو مش موجود
            if (!Schema::hasColumn('employee', 'shift_id')) {
                // الطريقة الحديثة مع مفتاح خارجي
                $table->foreignId('shift_id')
                    ->nullable()
                    ->after('basic_salary')   // غيّر مكانه لو حابب
                    ->constrained('shifts')
                    ->nullOnDelete();         // لما يتم حذف الشيفت يخلي القيمة NULL
            }
        });
    }

    public function down(): void
    {
        Schema::table('employee', function (Blueprint $table) {
            if (Schema::hasColumn('employee', 'shift_id')) {
                $table->dropConstrainedForeignId('shift_id'); // يحذف المفتاح والعمود
            }
        });
    }
};
