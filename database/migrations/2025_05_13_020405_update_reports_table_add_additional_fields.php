<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up()
  {
    Schema::table('reports', function (Blueprint $table) {
      $table->string('schedule')->default('none');
      $table->string('users_email')->nullable();
      $table->time('time')->nullable();
      $table->boolean('status')->default(true);
    });
  }

  public function down()
  {
    Schema::table('reports', function (Blueprint $table) {
      $table->dropColumn(['schedule', 'users_email', 'time', 'status']);
    });
  }
};
