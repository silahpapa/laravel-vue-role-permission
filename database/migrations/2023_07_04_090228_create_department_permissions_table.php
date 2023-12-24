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
        Schema::create('department_permissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('department_id');
            $table->unsignedBigInteger('permissions_id');
            $table->foreign('department_id')->references('id')->on('departments');
            $table->foreign('permissions_id')->references('id')->on('permissions');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('department_permissions');
    }
};
