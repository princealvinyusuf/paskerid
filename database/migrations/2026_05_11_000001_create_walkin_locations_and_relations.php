<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('walkin_locations')) {
            Schema::create('walkin_locations', function (Blueprint $table) {
                $table->id();
                $table->string('location_name')->unique();
                $table->timestamps();
            });
        }

        Schema::table('pasker_room', function (Blueprint $table) {
            if (!Schema::hasColumn('pasker_room', 'walkin_location_id')) {
                $table->foreignId('walkin_location_id')
                    ->nullable()
                    ->after('id')
                    ->constrained('walkin_locations')
                    ->nullOnDelete();
            }
        });

        Schema::table('pasker_facility', function (Blueprint $table) {
            if (!Schema::hasColumn('pasker_facility', 'walkin_location_id')) {
                $table->foreignId('walkin_location_id')
                    ->nullable()
                    ->after('id')
                    ->constrained('walkin_locations')
                    ->nullOnDelete();
            }
        });

        Schema::table('kemitraan', function (Blueprint $table) {
            if (!Schema::hasColumn('kemitraan', 'walkin_location_id')) {
                $table->foreignId('walkin_location_id')
                    ->nullable()
                    ->after('type_of_partnership_id')
                    ->constrained('walkin_locations')
                    ->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kemitraan', function (Blueprint $table) {
            if (Schema::hasColumn('kemitraan', 'walkin_location_id')) {
                $table->dropConstrainedForeignId('walkin_location_id');
            }
        });

        Schema::table('pasker_facility', function (Blueprint $table) {
            if (Schema::hasColumn('pasker_facility', 'walkin_location_id')) {
                $table->dropConstrainedForeignId('walkin_location_id');
            }
        });

        Schema::table('pasker_room', function (Blueprint $table) {
            if (Schema::hasColumn('pasker_room', 'walkin_location_id')) {
                $table->dropConstrainedForeignId('walkin_location_id');
            }
        });

        Schema::dropIfExists('walkin_locations');
    }
};
