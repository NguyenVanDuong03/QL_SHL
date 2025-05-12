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
        Schema::create('class_session_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('study_class_id');
            $table->unsignedBigInteger('lecturer_id');
            $table->unsignedBigInteger('class_session_registration_id');
            $table->unsignedBigInteger('room_id')->nullable();
            $table->tinyInteger('type')->comment('0:SHL cố định; 1:SHL linh hoạt');
            $table->date('proposed_at');
            $table->string('location')->nullable();
            $table->string('meeting_type')->nullable()->comment('0:Google Meet; 1:Zoom; 2:Microsoft Teams')->nullable();
            $table->string('meeting_id')->nullable();
            $table->string('meeting_password')->nullable();
            $table->string('meeting_url')->nullable();
            $table->string('title');
            $table->text('content');
            $table->tinyInteger('status')->default(0)->comment('0:pending; 1:approved; 2:rejected');
            $table->string('rejection_reason')->nullable();
            $table->timestamps();

            $table->foreign('study_class_id')->references('id')->on('study_classes')->onDelete('cascade');
            $table->foreign('lecturer_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('class_session_registration_id')->references('id')->on('class_session_registrations')->onDelete('cascade');
            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_session_requests');
    }
};
