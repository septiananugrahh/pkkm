<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nodes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('title');
            $table->enum('type', ['unsur', 'sub_unsur', 'indikator']);
            $table->integer('order')->default(0);
            $table->foreignId('year_id')->constrained('years')->onDelete('cascade');
            $table->timestamps();

            $table->foreign('parent_id')->references('id')->on('nodes')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nodes');
    }
};
