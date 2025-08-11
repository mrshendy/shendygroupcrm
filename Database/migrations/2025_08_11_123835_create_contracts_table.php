<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->foreignId('project_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('offer_id')->nullable()->constrained()->nullOnDelete();

            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();

            // نوع العقد
            $table->enum('type', ['maintenance','supply_install','marketing','software','data_entry','call_center'])->index();

            // إجمالي العقد
            $table->decimal('amount', 12, 2)->default(0);
            $table->boolean('include_tax')->default(false);

            // ملف العقد
            $table->string('contract_file')->nullable();

            // حالة عامة (اختياري)
            $table->enum('status', ['draft','active','suspended','completed','cancelled'])->default('draft')->index();

            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('contracts');
    }
};
