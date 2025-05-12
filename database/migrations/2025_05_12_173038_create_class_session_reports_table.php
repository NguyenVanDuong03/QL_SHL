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
            $table->text('value_1')->comment('Số sinh viên tham dự họp lớp/Tổng số sinh viên trong lớp');
            $table->tinyInteger('value_2')->comment('GVCN có tham gia buổi Sinh hoạt lớp');
            $table->text('value_3')->comment('Tình hình chính trị, tư tưởng, đạo đức, lối sống');
            $table->text('value_4')->comment('Tình hình học tập, rèn luyện');
            $table->text('value_5')->comment('Tình hình sinh viên nội trú');
            $table->text('value_6')->comment('Tình hình sinh viên ngoại trú');
            $table->text('value_7')->comment('Các hoạt động khác');
            $table->text('value_8')->comment('Đề xuất, kiến nghị với Khoa, Nhà trường');
            $table->binary('value_9')->comment('Minh chứng, hình ảnh buổi sinh hoạt lớp');
            $table->timestamps();

            $table->foreign('class_session_request_id')->references('id')->on('class_session_requests')->onDelete('cascade');
            $table->foreign('reporter_id')->references('id')->on('users')->onDelete('cascade');
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
