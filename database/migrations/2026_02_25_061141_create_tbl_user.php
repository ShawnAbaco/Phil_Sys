<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_tbl_user.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tbl_user', function (Blueprint $table) {
            $table->integer('user_id', true)->autoIncrement();
            $table->string('full_name', 999);
            $table->string('designation', 99);
            $table->integer('window_num');
            $table->integer('reg');
            $table->integer('prov');
            $table->integer('mun');
            $table->integer('brgy');
            $table->string('specific_loc', 99);
            $table->string('username', 99);
            $table->string('password', 255)->nullable();
            $table->string('password_hashed', 255)->nullable();

            $table->foreign('reg')->references('region_id')->on('table_region')->onDelete('cascade');
            $table->foreign('prov')->references('province_id')->on('table_province')->onDelete('cascade');
            $table->foreign('mun')->references('municipality_id')->on('table_municipality')->onDelete('cascade');
            $table->foreign('brgy')->references('barangay_id')->on('table_barangay')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tbl_user');
    }
};
