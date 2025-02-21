<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('alerts', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->bigInteger('type_id')->nullable();
            $table->boolean('every_day')->default(false);
            $table->time('time')->nullable();
            $table->Integer('quantity')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->string('description')->nullable();
            $table->boolean('status')->default(false);







            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alerts');
    }

};