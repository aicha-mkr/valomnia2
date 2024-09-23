<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailSentsTable extends Migration
{
    public function up()
    {
        Schema::create('email_sents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('recap_id');
            $table->string('recipient_email');
            $table->timestamp('sent_at')->nullable();
            $table->enum('status', ['sent', 'failed']);
            $table->timestamps();

            $table->foreign('recap_id')->references('id')->on('recaps')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('email_sents');
    }
}