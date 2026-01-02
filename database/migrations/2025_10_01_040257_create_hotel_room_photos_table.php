<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('hotel_room_photos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hotel_room_id');
            $table->string('photo');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('hotel_room_id')->references('id')->on('hotel_rooms')->onDelete('cascade');
        });
    }

    public function down() {
        Schema::dropIfExists('hotel_room_photos');
    }
};
