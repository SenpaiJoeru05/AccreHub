<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parameters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('area_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('slug')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
            $table->index('area_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parameters');
    }
};