<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('program_kemitraan_evaluation_rtl_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluation_id')
                ->constrained('program_kemitraan_evaluations')
                ->cascadeOnDelete();
            $table->unsignedSmallInteger('row_order');
            $table->text('issue')->nullable();
            $table->text('follow_up')->nullable();
            $table->string('responsible_person')->nullable();
            $table->date('target_date')->nullable();
            $table->text('completion_indicator')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();

            $table->index(['evaluation_id', 'row_order'], 'pk_eval_rtl_lookup_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('program_kemitraan_evaluation_rtl_items');
    }
};
