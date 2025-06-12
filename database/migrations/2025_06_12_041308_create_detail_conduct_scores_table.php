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
        Schema::create('detail_conduct_scores', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('conduct_criteria_id');
            $table->unsignedBigInteger('student_conduct_score_id');
            $table->integer('self_score')->default(0);
            $table->integer('class_score')->default(0)->nullable();
            $table->integer('final_score')->default(0)->nullable();
            $table->text('note')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('conduct_criteria_id')->references('id')->on('conduct_criterias')->onDelete('cascade');
            $table->foreign('student_conduct_score_id')->references('id')->on('student_conduct_scores')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_conduct_scores');
    }
};
