<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('transports', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['airplane','boat']);
            $table->string('name');
            $table->string('code')->nullable();
            $table->integer('capacity')->nullable();
            $table->decimal('price_per_seat', 15, 2)->nullable();
            $table->string('photo')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down() {
        Schema::dropIfExists('transports');
    }
};
