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
        Schema::create('class_session_reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('class_session_request_id');
            $table->unsignedBigInteger('reporter_id');
            $table->integer('attending_students')->comment('Số sinh viên tham dự họp lớp/Tổng số sinh viên trong lớp');
            $table->tinyInteger('teacher_attendance')->comment('GVCN có tham gia buổi Sinh hoạt lớp');
            $table->text('politics_ethics_lifestyle')->comment('Tình hình chính trị, tư tưởng, đạo đức, lối sống');
            $table->text('academic_training_status')->comment('Tình hình học tập, rèn luyện');
            $table->text('on_campus_student_status')->comment('Tình hình sinh viên nội trú');
            $table->text('off_campus_student_status')->comment('Tình hình sinh viên ngoại trú');
            $table->text('other_activities')->comment('Các hoạt động khác');
            $table->text('suggestions_to_faculty_university')->comment('Đề xuất, kiến nghị với Khoa, Nhà trường');
            $table->string('path')->comment('Minh chứng, hình ảnh buổi sinh hoạt lớp');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('class_session_request_id')->references('id')->on('class_session_requests')->onDelete('cascade');
            $table->foreign('reporter_id')->references('id')->on('students')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_session_reports');
    }
};
