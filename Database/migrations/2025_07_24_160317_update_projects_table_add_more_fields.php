<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateProjectsTableAddMoreFields extends Migration
{
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->unsignedBigInteger('country_id')->nullable()->after('id');
            $table->unsignedBigInteger('client_id')->nullable()->after('country_id');
            $table->string('project_type')->nullable()->after('description');
            $table->string('programming_type')->nullable()->after('project_type');
            $table->string('phase')->nullable()->after('programming_type');
            $table->text('details')->nullable()->after('phase');
            $table->date('start_date')->nullable()->after('details');
            $table->date('end_date')->nullable()->after('start_date');
            $table->string('priority')->default('medium')->after('end_date');

            // علاقات
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('set null');
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropForeign(['country_id']);
            $table->dropForeign(['client_id']);
            $table->dropColumn([
                'country_id', 'client_id', 'project_type', 'programming_type',
                'phase', 'details', 'start_date', 'end_date', 'priority'
            ]);
        });
    }
}