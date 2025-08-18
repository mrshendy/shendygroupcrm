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
    Schema::create('leaves', function (Blueprint $table) {
        $table->id();
        $table->foreignId('employee_id')->constrained()->onDelete('cascade');
        $table->foreignId('shift_id')->nullable()->constrained()->onDelete('set null');
        $table->enum('leave_type', ['sick','normal','casual','other']);
        $table->date('start_date');
        $table->date('end_date');
        $table->string('reason')->nullable();
        $table->enum('status', ['pending','approved','rejected'])->default('pending');
        $table->timestamps();
    });
}

    public function down()
    {
        Schema::dropIfExists('leaves');
    }
};
