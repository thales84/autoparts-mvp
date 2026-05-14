<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_compatibilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('vehicle_make_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('vehicle_model_id')->nullable()->constrained()->nullOnDelete();
            $table->smallInteger('year_from')->unsigned()->nullable();
            $table->smallInteger('year_to')->unsigned()->nullable();
            $table->string('notes')->nullable();
            $table->timestamps();

            $table->index(['product_id', 'vehicle_make_id', 'vehicle_model_id'], 'pc_product_make_model_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_compatibilities');
    }
};
