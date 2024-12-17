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
        Schema::create('auth_page_forms', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            $table->string('field');
            $table->string('type');
            $table->string('placeholder');
            $table->unsignedBigInteger('auth_page_id');
            $table->foreign('auth_page_id')->references('id')->on('auth_pages');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auth_page_forms');
    }
};
