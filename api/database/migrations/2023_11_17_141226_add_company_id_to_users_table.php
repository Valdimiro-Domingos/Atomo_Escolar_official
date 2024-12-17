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
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('company_id')->after('status');
            $table->foreign('company_id')->references('id')->on('companies');
            $table->unsignedBigInteger('role_id')->after('company_id');
            $table->foreign('role_id')->references('id')->on('roles');
            $table->unsignedBigInteger('departament_id')->after('role_id');
            $table->foreign('departament_id')->references('id')->on('departaments');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('users_company_id_foreign');
            $table->dropForeign('users_role_id_foreign');
            $table->dropForeign('users_departament_id_foreign');
            $table->dropColumn('company_id');
            $table->dropColumn('role_id');
            $table->dropColumn('departament_id');
        });
    }
};
