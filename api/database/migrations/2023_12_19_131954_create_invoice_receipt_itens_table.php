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
        Schema::create('invoice_receipt_itens', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('qtd');
            $table->decimal('price', 30,2)->nullable();
            $table->float('discount');
            $table->float('rate');
            $table->float('paid');
            $table->unsignedBigInteger('article_id') ;
            $table->unsignedBigInteger('invoice_receipt_id') ;
            $table->unsignedBigInteger('company_id') ;
            // $table->foreign('company_id')->references('id')->on('companies');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_receipt_itens');
    }
};
