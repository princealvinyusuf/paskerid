<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('program_kemitraan_evaluation_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluation_id')
                ->constrained('program_kemitraan_evaluations')
                ->cascadeOnDelete();
            $table->enum('form_type', ['A', 'B']);
            $table->string('section_key');
            $table->unsignedSmallInteger('indicator_number');
            $table->text('indicator_text');
            $table->unsignedTinyInteger('score')->nullable();
            $table->boolean('is_not_applicable')->default(false);
            $table->timestamps();

            $table->index(['evaluation_id', 'form_type', 'section_key'], 'pk_eval_answer_lookup_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('program_kemitraan_evaluation_answers');
    }
};
