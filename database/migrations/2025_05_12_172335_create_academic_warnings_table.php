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
            $table->unsignedBigInteger('semester_id');
            $table->date('warning_date')->nullable()->comment('Ngày cảnh cáo của giáo viên');
            $table->text('reason')->nullable()->comment('Lý do cảnh cáo của giáo viên');
            $table->string('credits');
            $table->integer(('gpa_10'));
            $table->float('gpa_4');
            $table->string('academic_status')->comment('Mức xử lý học vụ');
            $table->text('note')->nullable()->comment('Ghi chú');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->foreign('semester_id')->references('id')->on('semesters')->onDelete('cascade');
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
