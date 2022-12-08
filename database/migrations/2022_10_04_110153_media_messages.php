<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
return new class extends Migration
{
    public $timestamps = false;
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('media_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('messageId');
            $table->string('mimeType');
            $table->string('size');
            $table->string('name');
            $table->dateTime('insertedOn');
           $table->foreign('messageId')->references('id')->on('chat_messages')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::dropIfExists('media_messages');
    }
};
