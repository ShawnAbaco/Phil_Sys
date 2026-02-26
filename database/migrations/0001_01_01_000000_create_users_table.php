<?php
// database/migrations/0001_01_01_000000_create_users_table.php

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
        Schema::create('users', function (Blueprint $table) {
            // Laravel default fields
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();

            // YOUR CUSTOM FIELDS (from tbl_user) - NO FOREIGN KEYS YET
            $table->string('full_name', 999)->nullable();
            $table->string('designation', 99)->nullable();
            $table->integer('window_num')->nullable();
            $table->integer('reg')->nullable();
            $table->integer('prov')->nullable();
            $table->integer('mun')->nullable();
            $table->integer('brgy')->nullable();
            $table->string('specific_loc', 99)->nullable();
            $table->string('username', 99)->unique()->nullable();
            $table->string('password_hashed', 255)->nullable();

            // Add indexes for better performance
            $table->index('reg');
            $table->index('prov');
            $table->index('mun');
            $table->index('brgy');
            $table->index('username');
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
