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
        Schema::create('student_conduct_scores', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('semester_id');
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('conduct_criteria_id');
            $table->float('self_score')->comment('Điểm tự đánh giá');
            $table->float('class_score')->comment('Điểm giảng viên đánh giá');
            $table->float('final_score')->comment('Điểm CTSV đánh giá');
            $table->text('note')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('semester_id')->references('id')->on('semesters')->onDelete('cascade');
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->foreign('conduct_criteria_id')->references('id')->on('conduct_criterias')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_conduct_scores');
    }
};
