<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserIdToEmailTemplatesTable extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('email_templates', 'user_id')) {
            Schema::table('email_templates', function (Blueprint $table) {
                $table->unsignedBigInteger('user_id')->nullable()->after('id');
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('email_templates', 'user_id')) {
            Schema::table('email_templates', function (Blueprint $table) {
                $table->dropColumn('user_id');
            });
        }
    }
}