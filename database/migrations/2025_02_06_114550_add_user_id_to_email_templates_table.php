<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserIdToEmailTemplatesTable extends Migration
{
    public function up()
    {
        Schema::table('email_templates', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->after('id'); // Ajoute la colonne user_id
        });
    }

    public function down()
    {
        Schema::table('email_templates', function (Blueprint $table) {
            $table->dropColumn('user_id'); // Supprime la colonne si la migration est annulée
        });
    }
}
