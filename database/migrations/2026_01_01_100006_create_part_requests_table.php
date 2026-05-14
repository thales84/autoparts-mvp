<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('part_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('requested_part_name', 190);
            $table->string('reference', 120)->nullable();
            $table->string('vehicle_make', 120)->nullable();
            $table->string('vehicle_model', 120)->nullable();
            $table->smallInteger('vehicle_year')->unsigned()->nullable();
            $table->text('description')->nullable();
            $table->string('contact_name', 120);
            $table->string('contact_email', 190)->nullable();
            $table->string('contact_phone', 40)->nullable();
            $table->enum('status', ['new', 'in_progress', 'found', 'closed'])->default('new');
            $table->text('admin_notes')->nullable();
            $table->timestamps();

            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('part_requests');
    }
};
