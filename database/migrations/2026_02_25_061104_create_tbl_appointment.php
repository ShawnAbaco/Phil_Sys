<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_tbl_appointment.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
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
        });
    }

    public function down()
    {
        Schema::dropIfExists('tbl_appointment');
    }
};
