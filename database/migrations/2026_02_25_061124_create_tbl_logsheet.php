<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_tbl_logsheet.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
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
        });
    }

    public function down()
    {
        Schema::dropIfExists('tbl_logsheet');
    }
};
