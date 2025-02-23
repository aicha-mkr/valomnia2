<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
           $table->string('password');
            $table->string('organisation')->nullable();
            $table->string('role')->nullable();



            $table->string('token')->nullable();
            $table->string('cookies')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->timestamps();
        });


    }

    /**
     * Reverse the migrations.
     */
    
};