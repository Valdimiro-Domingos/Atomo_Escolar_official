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
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('designation')->notNullable();
            $table->string('description')->nullable();
            $table->float('price')->notNullable();
            $table->unsignedBigInteger('article_type_id')->notNullable();
            $table->unsignedBigInteger('article_category_id')->notNullable();
            $table->unsignedBigInteger('retention_id')->notNullable();
            $table->unsignedBigInteger('tax_id')->notNullable();
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
        Schema::dropIfExists('articles');
    }
};
