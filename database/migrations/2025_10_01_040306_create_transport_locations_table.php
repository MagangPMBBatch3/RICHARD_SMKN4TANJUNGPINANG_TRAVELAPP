<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('transport_locations', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['airport','port']);
            $table->string('name');
            $table->string('code')->nullable();
            $table->string('city');
            $table->string('photo')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down() {
        Schema::dropIfExists('transport_locations');
    }
};
