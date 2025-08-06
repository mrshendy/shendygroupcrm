<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('accounts', function (Blueprint $table) {
            if (!Schema::hasColumn('accounts', 'current_balance')) {
                $table->decimal('current_balance', 15, 2)->default(0)->after('opening_balance');
            }

            if (!Schema::hasColumn('accounts', 'status')) {
                $table->string('status')->default('مفعل')->after('current_balance');
            }

            if (!Schema::hasColumn('accounts', 'notes')) {
                $table->text('notes')->nullable()->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('accounts', function (Blueprint $table) {
            $table->dropColumn(['current_balance', 'status', 'notes']);
        });
    }
};

