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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();

            $table->string('designation');
            $table->string('nif')->unique();
            $table->string('logo')->nullable();
            $table->string('foundation_date')->nullable();
            $table->float('share_capital')->nullable();
            $table->string('contact')->nullable();
            $table->string('description')->nullable();
            $table->string('representative_name')->nullable();
            $table->string('representative_identification')->nullable();
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->string('address')->nullable();
            $table->string('email')->nullable();
            $table->date('dateIssure')->nullable();
            $table->date('dateDue')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('facebook')->nullable();
            $table->string('web_site')->nullable();
            $table->string('general_manager')->nullable();
            $table->string('pedagogical_manager')->nullable();
            $table->string('provincial_manager')->nullable();
            $table->string('municipal_manager')->nullable();
            $table->enum('status', ['0','1'])->default('1')->comment('status: 0=>disabled, 1=>enabled');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
