<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_tbl_typeofrc.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tbl_typeofrc', function (Blueprint $table) {
            $table->integer('trc_id', true)->autoIncrement();
            $table->string('type_rc', 20);
            $table->string('description', 999);
        });
    }

    public function down()
    {
        Schema::dropIfExists('tbl_typeofrc');
    }
};
