<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('sku', 80)->unique();
            $table->string('oem_reference', 120)->nullable();
            $table->string('name', 190);
            $table->string('slug', 220)->unique();
            $table->text('description');
            $table->enum('condition', ['used_good', 'used_fair', 'refurbished', 'for_parts'])->default('used_good');
            $table->decimal('price', 12, 2);
            $table->char('currency', 3)->default('EUR');
            $table->unsignedInteger('stock_quantity')->default(0);
            $table->enum('status', ['draft', 'active', 'inactive'])->default('draft');
            $table->string('main_image_path')->nullable();
            $table->string('location')->nullable();
            $table->timestamps();

            $table->index('name');
            $table->index('sku');
            $table->index('oem_reference');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
