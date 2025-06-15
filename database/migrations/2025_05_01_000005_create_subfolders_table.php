<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subfolders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('section_id')->constrained()->onDelete('cascade');
            $table->foreignId('parent_id')->nullable()->constrained('subfolders')->onDelete('cascade');
            $table->string('name');
            $table->string('slug')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
            $table->index('section_id');
            $table->index('parent_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subfolders');
    }
};