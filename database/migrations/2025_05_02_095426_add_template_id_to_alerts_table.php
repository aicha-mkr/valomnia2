<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::table('alerts', function (Blueprint $table) {
      $table->unsignedBigInteger('template_id')->nullable()->after('type_id');
    });
  }

  public function down(): void {
    Schema::table('alerts', function (Blueprint $table) {
      $table->dropColumn('template_id');
    });
  }
};
