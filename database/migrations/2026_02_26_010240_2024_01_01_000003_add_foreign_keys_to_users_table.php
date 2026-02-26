<?php
// database/migrations/2024_01_01_000003_add_foreign_keys_to_users_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add foreign keys to users table
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('reg')
                  ->references('region_id')
                  ->on('table_region')
                  ->onDelete('set null');

            $table->foreign('prov')
                  ->references('province_id')
                  ->on('table_province')
                  ->onDelete('set null');

            $table->foreign('mun')
                  ->references('municipality_id')
                  ->on('table_municipality')
                  ->onDelete('set null');

            $table->foreign('brgy')
                  ->references('barangay_id')
                  ->on('table_barangay')
                  ->onDelete('set null');
        });

        // Add foreign keys to tbl_appointment
        Schema::table('tbl_appointment', function (Blueprint $table) {
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');
        });

        // Add foreign keys to tbl_logsheet
        Schema::table('tbl_logsheet', function (Blueprint $table) {
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop foreign keys from users table
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['reg']);
            $table->dropForeign(['prov']);
            $table->dropForeign(['mun']);
            $table->dropForeign(['brgy']);
        });

        // Drop foreign keys from tbl_appointment
        Schema::table('tbl_appointment', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        // Drop foreign keys from tbl_logsheet
        Schema::table('tbl_logsheet', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });
    }
};
