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
            // Adding the new columns
            $table->text('content')->nullable(); // For storing the email body
            $table->string('template_type')->default('generic'); // Type of the email template
            $table->boolean('is_active')->default(true); // To mark whether the template is active or not
        });
    }
    
    public function down()
    {
        Schema::table('email_templates', function (Blueprint $table) {
            // Dropping the added columns in case of rollback
            $table->dropColumn('content');
            $table->dropColumn('template_type');
            $table->dropColumn('is_active');
        });
    }
    
};