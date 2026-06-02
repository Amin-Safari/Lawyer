<?php

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
        Schema::create('skill_clicks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('skill_id')->constrained()->onDelete('cascade');
            $table->foreignId('lawyer_id')->constrained()->onDelete('cascade');
            $table->foreignId('clicker_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->string('referrer')->nullable();
            $table->enum('type', ['view', 'click', 'call'])->default('click');
            $table->decimal('cost', 15)->default(0);
            $table->string('session_id')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['skill_id', 'lawyer_id']);
            $table->index(['created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skill_clicks');
    }
};
