<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateReportsTableNullableColumns extends Migration
{
  public function up()
  {
    Schema::table('reports', function (Blueprint $table) {
      $table->decimal('average_sales', 10, 2)->nullable()->change();
      $table->integer('total_quantities')->nullable()->change();
      $table->integer('total_clients')->nullable()->change();
      $table->text('top_selling_items')->nullable()->change();
    });
  }

  public function down()
  {
    Schema::table('reports', function (Blueprint $table) {
      $table->decimal('average_sales', 10, 2)->nullable(false)->change();
      $table->integer('total_quantities')->nullable(false)->change();
      $table->integer('total_clients')->nullable(false)->change();
      $table->text('top_selling_items')->nullable(false)->change();
    });
  }
}
