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
            $table->unsignedBigInteger('conduct_evaluation_period_id')->comment('Mã kỳ đánh giá hạnh kiểm');
            $table->unsignedBigInteger('student_id');
            $table->tinyInteger('status')->default(0);
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('conduct_evaluation_period_id')->references('id')->on('conduct_evaluation_periods')->onDelete('cascade');
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
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
