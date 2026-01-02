<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('booking_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('booking_id');
            $table->enum('item_type', ['hotel_room','transport_schedule'])->nullable();
            $table->unsignedBigInteger('reference_id');
            $table->integer('quantity')->default(1);
            $table->date('check_in')->nullable();
            $table->date('check_out')->nullable();
            $table->decimal('price', 12, 2);
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('booking_id')->references('id')->on('bookings')->onDelete('cascade');
        });
    }

    public function down() {
        Schema::dropIfExists('booking_items');
    }
};
