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
        Schema::create('monitorings', function (Blueprint $table) {
            $table->id();
            $table->string('patient_id');
            $table->unsignedBigInteger('user_id');
            $table->date('monitoring_date');
            $table->string('blood_pressure')->nullable();
            $table->string('heart_rate')->nullable();
            $table->string('respiratory_rate')->nullable();
            $table->string('body_temperature')->nullable();
            $table->string('oxygen_saturation')->nullable();
            $table->text('symptoms')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['Stable', 'Need Control', 'Need Referral'])->default('Stable');
            $table->time('monitoring_time')->nullable();
            $table->string('examination_focus')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monitorings');
    }
};
