<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('alerts_history', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('alert_id')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->integer('attemps')->nullable();
            $table->bigInteger('iduser')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alerts_history')  ;
    }
};
