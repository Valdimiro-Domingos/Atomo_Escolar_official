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
        Schema::create('mini_schedules', function (Blueprint $table) {
            $table->id();
            $table->string('designation')->nullable();
            $table->string('description')->nullable();
            $table->unsignedBigInteger('discipline_id');
            $table->unsignedBigInteger('schedule_id');
            $table->unsignedBigInteger('profeessor_id');
            $table->unsignedBigInteger('trimestre_id');
            $table->unsignedBigInteger('company_id');
            $table->text('file')->nullable();

            $table->unsignedBigInteger('school_year_id')->nullable();
            $table->unsignedBigInteger('classe_id')->nullable();
            $table->unsignedBigInteger('turma_id')->nullable();
            $table->unsignedBigInteger('course_id')->nullable();
            $table->unsignedBigInteger('class_room_id')->nullable();
            $table->unsignedBigInteger('period_id')->nullable();
            $table->foreign('company_id')->references('id')->on('companies');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mini_schedules');
    }
};
