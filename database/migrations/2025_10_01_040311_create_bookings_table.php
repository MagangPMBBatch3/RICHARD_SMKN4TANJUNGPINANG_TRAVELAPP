<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code');
            $table->unsignedBigInteger('user_id');
            $table->enum('booking_type', ['hotel','transport','mixed']);
            $table->decimal('total_price', 12, 2)->default(0.00);
            $table->enum('status', ['pending','confirmed','cancelled'])->default('pending');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down() {
        Schema::dropIfExists('bookings');
    }
};
