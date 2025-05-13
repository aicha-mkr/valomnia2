<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
  public function up()
  {
    Schema::table('reports', function (Blueprint $table) {
      $table->json('top_selling_items')->nullable()->after('total_clients');
    });
  }

  public function down()
  {
    Schema::table('reports', function (Blueprint $table) {
      $table->dropColumn('top_selling_items');
    });
  }

};
