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
    Schema::dropIfExists('report_type');
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::create('report_type', function (Blueprint $table) {
      $table->id();
      $table->string('name')->nullable(); // Adjust based on your original table structure
      $table->timestamps();
    });
  }
};
