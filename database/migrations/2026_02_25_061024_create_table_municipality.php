<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_table_municipality.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('table_municipality', function (Blueprint $table) {
            $table->integer('municipality_id', true)->autoIncrement();
            $table->integer('province_id')->nullable();
            $table->string('municipality_name', 100)->nullable();

            $table->index('municipality_name');
            $table->foreign('province_id')
                  ->references('province_id')
                  ->on('table_province')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('table_municipality');
    }
};
