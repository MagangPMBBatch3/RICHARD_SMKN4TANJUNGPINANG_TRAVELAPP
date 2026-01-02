<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('transport_schedules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transport_id');
            $table->unsignedBigInteger('origin_location_id')->nullable();
            $table->unsignedBigInteger('destination_location_id')->nullable();
            $table->dateTime('departure_time');
            $table->dateTime('arrival_time')->nullable();
            $table->decimal('price', 12, 2);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('transport_id')->references('id')->on('transports')->onDelete('cascade');
            $table->foreign('origin_location_id')->references('id')->on('transport_locations')->onDelete('set null');
            $table->foreign('destination_location_id')->references('id')->on('transport_locations')->onDelete('set null');
        });
    }

    public function down() {
        Schema::dropIfExists('transport_schedules');
    }
};
