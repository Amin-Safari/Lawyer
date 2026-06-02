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
        Schema::table('skills', function (Blueprint $table) {
            $table->decimal('click_price', 15, 2)->default(0)->after('name');
            $table->integer('total_clicks')->default(0)->after('click_price');
            $table->boolean('is_active')->default(true)->after('total_clicks');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('skills', function (Blueprint $table) {
            $table->dropColumn(['click_price', 'total_clicks', 'is_active']);
        });
    }
};
