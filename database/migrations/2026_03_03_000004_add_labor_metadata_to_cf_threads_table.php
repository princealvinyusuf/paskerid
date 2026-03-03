<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cf_threads', function (Blueprint $table) {
            $table->string('job_role', 120)->nullable()->after('sector');
            $table->string('province', 120)->nullable()->after('job_role');
            $table->string('city', 120)->nullable()->after('province');
            $table->string('work_type', 50)->nullable()->after('city');
            $table->string('salary_range', 120)->nullable()->after('work_type');
            $table->string('experience_level', 50)->nullable()->after('salary_range');

            $table->index(['province', 'city']);
            $table->index(['work_type', 'experience_level']);
            $table->index('job_role');
        });
    }

    public function down(): void
    {
        Schema::table('cf_threads', function (Blueprint $table) {
            $table->dropIndex(['province', 'city']);
            $table->dropIndex(['work_type', 'experience_level']);
            $table->dropIndex(['job_role']);
            $table->dropColumn([
                'job_role',
                'province',
                'city',
                'work_type',
                'salary_range',
                'experience_level',
            ]);
        });
    }
};
