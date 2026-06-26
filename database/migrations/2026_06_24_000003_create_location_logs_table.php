<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('location_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('latitude');
            $table->string('longitude');
            $table->string('accuracy')->nullable();
            $table->string('altitude')->nullable();
            $table->string('speed')->nullable();
            $table->string('heading')->nullable();
            $table->string('source')->default('gps');
            $table->timestamp('recorded_at')->useCurrent();
            $table->timestamps();

            $table->index('user_id');
            $table->index('recorded_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('location_logs');
    }
};
