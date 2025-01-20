<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateEmailTemplatesTable extends Migration
{
    public function up()
    {
        Schema::table('email_templates', function (Blueprint $table) {
            // Drop the body column if it exists
            if (Schema::hasColumn('email_templates', 'body')) {
                $table->dropColumn('body');
            }
        });
    }

    public function down()
    {
        Schema::table('email_templates', function (Blueprint $table) {
            // Re-add body column in case of rollback
            if (!Schema::hasColumn('email_templates', 'body')) {
                $table->text('body')->after('subject');
            }
        });
    }
}