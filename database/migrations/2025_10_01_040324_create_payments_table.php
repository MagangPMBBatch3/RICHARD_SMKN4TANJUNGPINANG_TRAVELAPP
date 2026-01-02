<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('booking_id');
            $table->decimal('amount', 12, 2);
            $table->string('payment_method')->nullable();
            $table->string('proof')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->enum('status', ['pending','confirmed','failed'])->default('pending');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('booking_id')->references('id')->on('bookings')->onDelete('cascade');
        });
    }

    public function down() {
        Schema::dropIfExists('payments');
    }
};
