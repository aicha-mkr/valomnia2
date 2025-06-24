<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailSentsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('email_sents')) {
            Schema::create('email_sents', function (Blueprint $table) {
                $table->id();
                // $table->foreignId('recap_id')->constrained('recaps')->onDelete('cascade');
                $table->string('recipient_email');
                $table->timestamp('sent_at')->nullable();
                $table->enum('status', ['sent', 'failed']);
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('email_sents');
    }
}