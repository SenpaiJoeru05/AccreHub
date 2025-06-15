<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('path');
            $table->foreignId('area_id')->constrained()->onDelete('cascade');
            $table->foreignId('parameter_id')->constrained()->onDelete('cascade');
            $table->foreignId('section_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('subfolder_id')->nullable()->constrained()->onDelete('cascade');
            $table->integer('year');
            $table->text('description')->nullable();
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->index('area_id');
            $table->index('parameter_id');
            $table->index('section_id');
            $table->index('subfolder_id');
            $table->index('uploaded_by');
            $table->index('year');
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};