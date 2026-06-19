<?php
// database/migrations/xxxx_create_verification_codes_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('verification_codes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('code', 6);
            $table->string('type'); // email, phone, password
            $table->string('target'); // email address or phone number
            $table->timestamp('expires_at');
            $table->timestamp('used_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'type', 'code']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('verification_codes');
    }
};
