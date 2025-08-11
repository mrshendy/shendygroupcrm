<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('contract_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contract_id')->constrained()->cascadeOnDelete();

            // نوع الدفعة: مرحلة أو شهري
            $table->enum('payment_type', ['milestone','monthly'])->index();

            // للمرحلية
            $table->string('title')->nullable(); // مثال: "دفعة التوريد"
            $table->enum('stage', ['contract','supply','training','operation','migration','soft_live','maintenance'])->nullable();

            // للشهري
            $table->date('period_month')->nullable(); // نحدد الشهر (YYYY-MM-01)

            // مشترك
            $table->date('due_date')->nullable();
            $table->enum('condition', ['date','stage'])->default('date'); // شرط التحصيل
            $table->decimal('amount', 12, 2)->default(0);
            $table->boolean('include_tax')->default(false);
            $table->boolean('is_paid')->default(false);

            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('contract_payments');
    }
};
