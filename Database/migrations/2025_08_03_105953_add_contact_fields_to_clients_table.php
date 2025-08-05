<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddContactFieldsToClientsTable extends Migration
{
    public function up()
    {
        Schema::table('clients', function (Blueprint $table) {
            if (!Schema::hasColumn('clients', 'contact_name')) {
                $table->string('contact_name')->nullable()->after('country');
            }

            if (!Schema::hasColumn('clients', 'contact_job')) {
                $table->string('contact_job')->nullable()->after('contact_name');
            }

            if (!Schema::hasColumn('clients', 'contact_phone')) {
                $table->string('contact_phone')->nullable()->after('contact_job');
            }

            if (!Schema::hasColumn('clients', 'contact_email')) {
                $table->string('contact_email')->nullable()->after('contact_phone');
            }

            if (!Schema::hasColumn('clients', 'is_primary')) {
                $table->boolean('is_primary')->default(false)->after('contact_email');
            }
        });
    }

    public function down()
    {
        Schema::table('clients', function (Blueprint $table) {
            if (Schema::hasColumn('clients', 'contact_name')) {
                $table->dropColumn('contact_name');
            }

            if (Schema::hasColumn('clients', 'contact_job')) {
                $table->dropColumn('contact_job');
            }

            if (Schema::hasColumn('clients', 'contact_phone')) {
                $table->dropColumn('contact_phone');
            }

            if (Schema::hasColumn('clients', 'contact_email')) {
                $table->dropColumn('contact_email');
            }

            if (Schema::hasColumn('clients', 'is_primary')) {
                $table->dropColumn('is_primary');
            }
        });
    }
}
