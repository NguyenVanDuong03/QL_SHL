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
        Schema::create('conduct_evaluation_phases', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('conduct_evaluation_period_id');
            $table->tinyInteger('role')->comment('0: SV, 1: GVCN, 2: VPK');
            $table->dateTime('open_date');
            $table->dateTime('end_date');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('conduct_evaluation_period_id')->references('id')->on('conduct_evaluation_periods')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conduct_evaluation_phases');
    }
};
