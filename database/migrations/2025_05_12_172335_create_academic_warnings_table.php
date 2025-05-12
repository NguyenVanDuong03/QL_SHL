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
        Schema::create('academic_warnings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->date('warning_date');
            $table->text('reason')->comment('Lý do cảnh cáo');
            $table->string('semester_1_name');
            $table->integer(('semester_1_credits'));
            $table->float('semester_1_gpa_10');
            $table->float('semester_1_gpa_4');
            $table->string('semester_2_name')->nullable();
            $table->integer('semester_2_credits')->nullable();
            $table->float('semester_2_gpa_10')->nullable();
            $table->float('semester_2_gpa_4')->nullable();
            $table->integer('total_credits_all');
            $table->integer('total_credists_2_recent_semesters');
            $table->string('academic_status_latest')->comment('Mức xử lý học vụ kỳ gần nhất');
            $table->string('academic_status_summary')->comment('Mức xử lý học vụ tổng hợp');
            $table->text('note')->nullable()->comment('Ghi chú');
            $table->timestamps();

            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academic_warnings');
    }
};
