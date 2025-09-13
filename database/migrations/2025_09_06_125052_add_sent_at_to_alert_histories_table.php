<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('alert_histories', function (Blueprint $table) {
            $table->timestamp('sent_at')->nullable()->after('attempts');
            $table->timestamp('last_attempt_at')->nullable()->after('sent_at');
        });
    }

    public function down()
    {
        Schema::table('alert_histories', function (Blueprint $table) {
            $table->dropColumn(['sent_at', 'last_attempt_at']);
        });
    }
};