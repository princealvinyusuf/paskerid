<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	public function up(): void
	{
		if (!Schema::hasTable('kemitraan_pasker_room')) {
			Schema::create('kemitraan_pasker_room', function (Blueprint $table) {
				$table->id();
				$table->unsignedBigInteger('kemitraan_id');
				$table->unsignedBigInteger('pasker_room_id');
				$table->timestamps();
				$table->foreign('kemitraan_id')->references('id')->on('kemitraan')->onDelete('cascade');
				$table->foreign('pasker_room_id')->references('id')->on('pasker_room')->onDelete('cascade');
				$table->unique(['kemitraan_id', 'pasker_room_id']);
			});
		}

		if (!Schema::hasTable('kemitraan_pasker_facility')) {
			Schema::create('kemitraan_pasker_facility', function (Blueprint $table) {
				$table->id();
				$table->unsignedBigInteger('kemitraan_id');
				$table->unsignedBigInteger('pasker_facility_id');
				$table->timestamps();
				$table->foreign('kemitraan_id')->references('id')->on('kemitraan')->onDelete('cascade');
				$table->foreign('pasker_facility_id')->references('id')->on('pasker_facility')->onDelete('cascade');
				$table->unique(['kemitraan_id', 'pasker_facility_id']);
			});
		}
	}

	public function down(): void
	{
		Schema::dropIfExists('kemitraan_pasker_facility');
		Schema::dropIfExists('kemitraan_pasker_room');
	}
}; 