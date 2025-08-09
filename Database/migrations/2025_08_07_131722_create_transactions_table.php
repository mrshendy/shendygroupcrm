<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();

            // علاقات أساسية
            $table->foreignId('account_id')->constrained('accounts')->cascadeOnDelete();
            $table->foreignId('item_id')->constrained('items')->cascadeOnDelete();

            // عميل (اختياري) - عند التحصيل من عميل مثلاً
            $table->foreignId('client_id')
                  ->nullable()
                  ->constrained('clients')
                  ->cascadeOnUpdate()
                  ->nullOnDelete();

            // الحقول
            $table->enum('type', ['مصروفات', 'تحصيل']);
            $table->decimal('amount', 15, 2);
            $table->date('transaction_date')->nullable();
            $table->text('notes')->nullable();
            $table->text('collection_type')->nullable();

            //log user
            $table->string('user_add', 30);


            // تواريخ
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
