<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifySessionsTable extends Migration
{
    public function up()
    {
        Schema::table('sessions', function (Blueprint $table) {
            $table->string('id')->change();
            $table->integer('last_activity')->change();
        });
    }

    public function down()
    {
        Schema::table('sessions', function (Blueprint $table) {
            $table->string('id')->change();
            $table->integer('last_activity')->change();
        });
    }
}