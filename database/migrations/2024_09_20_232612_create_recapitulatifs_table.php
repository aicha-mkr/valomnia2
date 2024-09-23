<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecapsTable extends Migration
{
    public function up()
    {
        Schema::create('recaps', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->date('date');
            $table->integer('total_orders');
            $table->decimal('total_revenue', 10, 2);
            $table->decimal('average_sales', 10, 2);
            $table->integer('total_quantities');
            $table->unsignedInteger('total_clients');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('recaps');
    }
}