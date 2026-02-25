<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_table_barangay.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('table_barangay', function (Blueprint $table) {
            $table->integer('barangay_id', true)->autoIncrement();
            $table->integer('municipality_id');
            $table->string('barangay_name', 100);

            $table->index('barangay_name');
            $table->foreign('municipality_id')
                  ->references('municipality_id')
                  ->on('table_municipality')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('table_barangay');
    }
};
