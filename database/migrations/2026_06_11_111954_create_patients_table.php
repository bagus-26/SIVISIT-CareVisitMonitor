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
        Schema::create('patients', function (Blueprint $table) {
            $table->string('patient_id')->unique();
            $table->string('patient_name');
            $table->string('nik_dummy')->nullable();
            $table->date('datebirth');
            $table->enum('gender', ['Male', 'Female']);
            $table->text('address');
            $table->string('family_phone');
            $table->string('patient_category');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
