<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('node_id');
            $table->string('file_name');
            $table->string('file_path');
            $table->string('file_type')->nullable(); // pdf, jpg, png, dll
            $table->timestamps();

            $table->foreign('node_id')->references('id')->on('nodes')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
