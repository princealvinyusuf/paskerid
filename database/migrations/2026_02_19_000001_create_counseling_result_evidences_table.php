<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('counseling_result_evidences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('counseling_result_id')->constrained('counseling_results')->cascadeOnDelete();
            $table->string('file_path');
            $table->string('original_name')->nullable();
            $table->string('mime_type', 191)->nullable();
            $table->unsignedBigInteger('file_size')->nullable();
            $table->timestamps();

            $table->index(['counseling_result_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('counseling_result_evidences');
    }
};


