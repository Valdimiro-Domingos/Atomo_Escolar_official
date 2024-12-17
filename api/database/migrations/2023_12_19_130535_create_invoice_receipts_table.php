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
        Schema::create('invoice_receipts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id') ;
            $table->string('invoice_number');
            $table->string('date_of_issue');
            $table->string('due_date');
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('form_of_payment_id') ;
            $table->unsignedBigInteger('enrollment_id')->nullable() ;
            $table->unsignedBigInteger('user_id') ;
            $table->enum('status', ['0','1'])->default('1')->comment('status: 0=>disabled, 1=>enabled');
            $table->enum('coin', ['Kwanza','Dollar'])->default('Kwanza');
            $table->decimal('total', 30,2)->nullable();
            $table->decimal('subtotal', 30,2)->nullable();
            $table->decimal('retention', 30,2)->nullable();
            $table->decimal('discount', 30,2)->nullable();
            $table->decimal('tax', 30,2)->nullable();
            $table->foreign('company_id')->references('id')->on('companies');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_receipts');
    }
};
