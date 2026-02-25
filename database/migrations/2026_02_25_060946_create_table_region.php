<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_table_region.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('table_region', function (Blueprint $table) {
            $table->integer('region_id', true)->autoIncrement();
            $table->string('region_name', 50)->unique();
            $table->string('region_description', 100);
        });
    }

    public function down()
    {
        Schema::dropIfExists('table_region');
    }
};
