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
        Schema::table('email_templates', function (Blueprint $table) {
            $table->foreignId('alert_id')->nullable()->constrained('alerts')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('email_templates', function (Blueprint $table) {
            $table->dropForeign(['alert_id']);
            $table->dropColumn('alert_id');
        });
    }
    
};