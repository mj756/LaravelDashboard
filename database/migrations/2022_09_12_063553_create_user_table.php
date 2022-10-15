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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('password');
            $table->char('gender',1);
            $table->dateTime('dob')->nullable();
            $table->string('profileImage')->default('');
            $table->dateTime('insertedOn')->default(Carbon::now()->utc()->toDateTimeString());
            $table->dateTime('updatedOn')->default(Carbon::now()->utc()->toDateTimeString());
            $table->dateTime('deletedOn')->nullable();
            $table->boolean('isDeleted')->default(false);
            $table->boolean('isSocialUser')->default(false);
            $table->string('remember_token')->default('');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
    
};
