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
        Schema::create('conduct_criteria_evidences', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_conduct_score_id');
            $table->binary('image_path');
            $table->string('description');
            $table->timestamps();

            $table->foreign('student_conduct_score_id')->references('id')->on('student_conduct_scores')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conduct_criteria_evidences');
    }
};
