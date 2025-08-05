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
       Schema::create('offer_followups', function (Blueprint $table) {
    $table->id();
    $table->foreignId('offer_id')->constrained()->onDelete('cascade');
    $table->date('follow_up_date');
    $table->string('type');
    $table->text('description')->nullable();
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
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
        Schema::dropIfExists('offer_followups');
    }
};
