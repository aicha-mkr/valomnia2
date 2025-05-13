<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterEmailTemplatesAlertIdForeignKey extends Migration
{
  public function up()
  {
    Schema::table('email_templates', function (Blueprint $table) {
      // Drop existing foreign key
      $table->dropForeign(['alert_id']);
      // Add new foreign key referencing type_alerts
      $table->foreignId('alert_id')->nullable()->change()->constrained('type_alerts')->onDelete('cascade');
    });
  }

  public function down()
  {
    Schema::table('email_templates', function (Blueprint $table) {
      $table->dropForeign(['alert_id']);
      $table->foreignId('alert_id')->nullable()->change()->constrained('alerts')->onDelete('cascade');
    });
  }
}
