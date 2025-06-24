<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportsTable extends Migration
{
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->integer('total_orders')->nullable();
            $table->decimal('total_revenue', 10, 2)->nullable();
            $table->decimal('average_sales', 10, 2)->nullable();
            $table->integer('total_quantities')->nullable();
            $table->integer('total_clients')->nullable();
            $table->text('top_selling_items')->nullable();
            $table->date('startDate')->nullable();
            $table->date('endDate')->nullable();
            $table->enum('schedule', ['none', 'daily', 'weekly', 'monthly'])->default('none');
            $table->text('users_email');
            $table->time('time')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('reports');
    }
} 