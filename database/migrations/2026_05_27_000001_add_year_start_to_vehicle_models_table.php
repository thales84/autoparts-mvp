<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vehicle_models', function (Blueprint $table) {
            $table->unsignedSmallInteger('year_start')->nullable()->after('slug');
        });
    }

    public function down(): void
    {
        Schema::table('vehicle_models', function (Blueprint $table) {
            $table->dropColumn('year_start');
        });
    }
};
