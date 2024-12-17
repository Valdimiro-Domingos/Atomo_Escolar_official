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
        Schema::create('grades', function (Blueprint $table) {
            $table->id(); 
            
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('mini_schedule_id');
            $table->float('continuous_evaluation_average');
            $table->float('teachers_test_score');
            $table->float('quarterly_test_score');
            $table->float('quarterly_average');
            $table->unsignedBigInteger('company_id') ;
            $table->foreign('company_id')->references('id')->on('companies');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};
