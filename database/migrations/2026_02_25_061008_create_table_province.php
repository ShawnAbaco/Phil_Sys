<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_table_province.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('table_province', function (Blueprint $table) {
            $table->integer('province_id', true)->autoIncrement();
            $table->integer('region_id');
            $table->string('province_name', 100);

            $table->index('province_name');
            $table->foreign('region_id')
                  ->references('region_id')
                  ->on('table_region')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('table_province');
    }
};
