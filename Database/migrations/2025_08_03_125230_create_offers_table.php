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
   public function up()
{
    Schema::create('offers', function (Blueprint $table) {
        $table->id();
        $table->foreignId('client_id')->constrained()->onDelete('cascade');
        $table->foreignId('project_id')->nullable()->constrained()->onDelete('set null');
        $table->date('start_date')->nullable();
        $table->date('end_date')->nullable();
        $table->text('details')->nullable();
        $table->decimal('amount', 10, 2)->default(0);
        $table->boolean('include_tax')->default(false);
        $table->text('description')->nullable();
        $table->string('file_path')->nullable(); // لرفع الملفات
        $table->string('status')->nullable(); 
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('offers');
    }
};
