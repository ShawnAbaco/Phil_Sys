<?php
// database/migrations/2024_01_01_000000_create_nid_operation_tables.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // =============================================
        // TABLE: table_region (Parent table)
        // =============================================
        Schema::create('table_region', function (Blueprint $table) {
            $table->integer('region_id', true)->autoIncrement();
            $table->string('region_name', 50)->unique();
            $table->string('region_description', 100);
            $table->timestamps();
        });

        // =============================================
        // TABLE: table_province (Depends on region)
        // =============================================
        Schema::create('table_province', function (Blueprint $table) {
            $table->integer('province_id', true)->autoIncrement();
            $table->integer('region_id');
            $table->string('province_name', 100);
            $table->timestamps();

            $table->index('province_name');
            $table->foreign('region_id')
                  ->references('region_id')
                  ->on('table_region')
                  ->onDelete('cascade');
        });

        // =============================================
        // TABLE: table_municipality (Depends on province)
        // =============================================
        Schema::create('table_municipality', function (Blueprint $table) {
            $table->integer('municipality_id', true)->autoIncrement();
            $table->integer('province_id')->nullable();
            $table->string('municipality_name', 100)->nullable();
            $table->timestamps();

            $table->index('municipality_name');
            $table->foreign('province_id')
                  ->references('province_id')
                  ->on('table_province')
                  ->onDelete('cascade');
        });

        // =============================================
        // TABLE: table_barangay (Depends on municipality)
        // =============================================
        Schema::create('table_barangay', function (Blueprint $table) {
            $table->integer('barangay_id', true)->autoIncrement();
            $table->integer('municipality_id');
            $table->string('barangay_name', 100);
            $table->timestamps();

            $table->index('barangay_name');
            $table->foreign('municipality_id')
                  ->references('municipality_id')
                  ->on('table_municipality')
                  ->onDelete('cascade');
        });

        // =============================================
        // TABLE: tbl_typeofrc (Type of Registration Center)
        // =============================================
        Schema::create('tbl_typeofrc', function (Blueprint $table) {
            $table->integer('trc_id', true)->autoIncrement();
            $table->string('type_rc', 20);
            $table->string('description', 999);
            $table->timestamps();
        });

        // =============================================
        // TABLE: tbl_appointment (Appointments)
        // =============================================
        Schema::create('tbl_appointment', function (Blueprint $table) {
            $table->integer('n_id', true)->autoIncrement();
            $table->string('q_id', 255);
            $table->dateTime('date')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->string('queue_for', 99);
            $table->string('fname', 99);
            $table->string('mname', 99);
            $table->string('lname', 99);
            $table->string('suffix', 3);
            $table->string('age_category', 99);
            $table->string('trn', 29);
            $table->date('birthdate');
            $table->string('PCN', 16);
            $table->string('window_num', 9);
            $table->dateTime('time_catered')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamps();
        });

        // =============================================
        // TABLE: tbl_logsheet (Log sheets)
        // =============================================
        Schema::create('tbl_logsheet', function (Blueprint $table) {
            $table->integer('ls_id', true)->autoIncrement();
            $table->string('ls_fname', 255);
            $table->string('ls_mname', 255);
            $table->string('ls_lname', 255);
            $table->string('ls_suf', 3);
            $table->string('ls_category', 255);
            $table->string('ls_accompany', 255);
            $table->integer('ls_accom_pcn');
            $table->string('ls_overseas', 11);
            $table->string('ls_dswd', 11);
            $table->integer('ls_trn');
            $table->string('ls_ephlid_stat', 255);
            $table->date('ls_ephilid_date');
            $table->string('ls_contact', 11);
            $table->string('ls_services_availed', 255);
            $table->date('ls_date');
            $table->integer('ls_oldtrn_recapture');
            $table->string('ls_rko', 255);
            $table->string('ls_cntr_mun', 255);
            $table->string('ls_cntr_brgy', 255);
            $table->string('ls_cntr_spcloc', 255);
            $table->string('ls_cbms', 255);
            $table->string('ls_cbms_addr', 255);
            $table->date('ls_birth');
            $table->string('ls_ffname', 255);
            $table->string('ls_fmname', 255);
            $table->string('ls_flname', 255);
            $table->string('ls_fsuf', 255);
            $table->string('ls_mfname', 255);
            $table->string('ls_mmname', 255);
            $table->string('ls_mlname', 255);
            $table->string('ls_authentication', 255);
            $table->date('ls_birthdate');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_logsheet');
        Schema::dropIfExists('tbl_appointment');
        Schema::dropIfExists('tbl_typeofrc');
        Schema::dropIfExists('table_barangay');
        Schema::dropIfExists('table_municipality');
        Schema::dropIfExists('table_province');
        Schema::dropIfExists('table_region');
    }
};
