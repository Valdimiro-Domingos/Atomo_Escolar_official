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
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id') ;
            $table->string('enrollment_number');
            $table->enum('status', ['0','1'])->default('1')->comment('status: 0=>disabled, 1=>enabled');
            $table->enum('paid', ['0','1'])->default('1')->comment('status: 0=>disabled, 1=>enabled');
            $table->enum('dropout', ['0','1'])->default('0')->comment('status: 0=>disabled, 1=>enabled');
            $table->text('observation')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('school_year_id');
            $table->unsignedBigInteger('classe_id');
            $table->unsignedBigInteger('turma_id');
            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('class_room_id');
            $table->unsignedBigInteger('period_id');
            $table->enum('message', ['matricula', 'confirmacao'])->nullable()->default('matricula');
            $table->foreign('company_id')->references('id')->on('companies');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};
