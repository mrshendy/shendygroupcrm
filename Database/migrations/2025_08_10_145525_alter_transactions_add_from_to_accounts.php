<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1) أضف الأعمدة الجديدة لو مش موجودة
        if (!Schema::hasColumn('transactions', 'from_account_id') || !Schema::hasColumn('transactions', 'to_account_id')) {
            Schema::table('transactions', function (Blueprint $table) {
                if (!Schema::hasColumn('transactions', 'from_account_id')) {
                    $table->foreignId('from_account_id')->nullable()->constrained('accounts')->nullOnDelete();
                }
                if (!Schema::hasColumn('transactions', 'to_account_id')) {
                    $table->foreignId('to_account_id')->nullable()->constrained('accounts')->nullOnDelete();
                }
            });
        }

        // 2) ترحيل البيانات من account_id إلى from/to حسب المتاح
        DB::transaction(function () {
            $hasTxnType = Schema::hasColumn('transactions', 'transaction_type');
            $hasType    = Schema::hasColumn('transactions', 'type');

            if ($hasTxnType) {
                // لو عندك transaction_type
                DB::table('transactions')
                    ->whereNotNull('account_id')
                    ->where('transaction_type', 'مصروفات')
                    ->update(['from_account_id' => DB::raw('account_id')]);

                DB::table('transactions')
                    ->whereNotNull('account_id')
                    ->where('transaction_type', '!=', 'مصروفات')
                    ->update(['to_account_id' => DB::raw('account_id')]);
            } elseif ($hasType) {
                // لو عندك type (إنجليزي/عربي)
                DB::table('transactions')
                    ->whereNotNull('account_id')
                    ->whereIn('type', ['مصروفات', 'expense', 'expenses', 'out', 'debit'])
                    ->update(['from_account_id' => DB::raw('account_id')]);

                DB::table('transactions')
                    ->whereNotNull('account_id')
                    ->whereNotIn('type', ['مصروفات', 'expense', 'expenses', 'out', 'debit'])
                    ->update(['to_account_id' => DB::raw('account_id')]);
            } else {
                // لو مافيش ولا عمود يحدد الاتجاه: نحافظ على المرجعية على الأقل
                DB::table('transactions')
                    ->whereNotNull('account_id')
                    ->update(['from_account_id' => DB::raw('account_id')]);
            }
        });

        // 3) احذف account_id لو موجود (بعد الترحيل)
        if (Schema::hasColumn('transactions', 'account_id')) {
            Schema::table('transactions', function (Blueprint $table) {
                try { $table->dropForeign(['account_id']); } catch (\Throwable $e) {}
                $table->dropColumn('account_id');
            });
        }

        // (اختياري) فهارس
        Schema::table('transactions', function (Blueprint $table) {
            try { $table->index('from_account_id'); } catch (\Throwable $e) {}
            try { $table->index('to_account_id'); } catch (\Throwable $e) {}
        });
    }

    public function down(): void
    {
        // رجوع account_id
        if (!Schema::hasColumn('transactions', 'account_id')) {
            Schema::table('transactions', function (Blueprint $table) {
                $table->foreignId('account_id')->nullable()->constrained('accounts')->nullOnDelete();
            });
        }

        DB::table('transactions')->update([
            'account_id' => DB::raw('COALESCE(from_account_id, to_account_id)')
        ]);

        Schema::table('transactions', function (Blueprint $table) {
            try { $table->dropForeign(['from_account_id']); } catch (\Throwable $e) {}
            try { $table->dropForeign(['to_account_id']); } catch (\Throwable $e) {}
            try { $table->dropIndex(['from_account_id']); } catch (\Throwable $e) {}
            try { $table->dropIndex(['to_account_id']); } catch (\Throwable $e) {}
            $table->dropColumn(['from_account_id', 'to_account_id']);
        });
    }
};
