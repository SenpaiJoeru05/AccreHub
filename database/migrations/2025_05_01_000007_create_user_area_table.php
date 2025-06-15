<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_area', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('area_id')->constrained()->onDelete('cascade');
            $table->primary(['user_id', 'area_id']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_area');
    }
};