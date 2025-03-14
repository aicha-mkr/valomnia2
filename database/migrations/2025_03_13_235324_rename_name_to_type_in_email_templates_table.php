<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameNameToTypeInEmailTemplatesTable extends Migration
{
    public function up()
    {
        Schema::table('email_templates', function (Blueprint $table) {
            $table->renameColumn('name', 'type'); // Change 'name' to 'type'
        });
    }

    public function down()
    {
        Schema::table('email_templates', function (Blueprint $table) {
            $table->renameColumn('type', 'name'); // Revert change if needed
        });
    }
}