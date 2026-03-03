<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('cf_verified_role', ['employer', 'jobseeker'])->nullable()->after('remember_token');
            $table->timestamp('cf_verified_at')->nullable()->after('cf_verified_role');
            $table->index('cf_verified_role');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['cf_verified_role']);
            $table->dropColumn(['cf_verified_role', 'cf_verified_at']);
        });
    }
};
