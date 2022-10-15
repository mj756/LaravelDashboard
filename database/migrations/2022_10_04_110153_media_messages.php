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
            $table->integer('messageId');
            $table->string('mimeType');
            $table->string('size');
            $table->string('name');
            $table->dateTime('insertedOn')->default(Carbon::now()->toDateTimeString());  
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
