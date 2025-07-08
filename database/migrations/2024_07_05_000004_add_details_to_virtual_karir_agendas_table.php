<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('virtual_karir_agendas', function (Blueprint $table) {
            $table->string('location')->nullable()->after('date');
            $table->string('organizer')->nullable()->after('location');
            $table->string('image_url')->nullable()->after('organizer');
            $table->string('registration_url')->nullable()->after('image_url');
        });
    }

    public function down(): void
    {
        Schema::table('virtual_karir_agendas', function (Blueprint $table) {
            $table->dropColumn(['location', 'organizer', 'image_url', 'registration_url']);
        });
    }
}; 