<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('specialization');
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('qualifications')->nullable();
            $table->decimal('consultation_fee', 10, 2)->default(0);
            $table->json('available_days')->nullable(); // e.g. ["Monday","Wednesday","Friday"]
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doctors');
    }
};