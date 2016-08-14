<?php

use Illuminate\Database\Schema\Blueprint;

use Illuminate\Database\Migrations\Migration;


class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->string('name')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->boolean('active')->default(TRUE);
            $table->string('salt');
            $table->bigInteger('access_level_id')->unsigned()->nullable();
            $table->bigInteger('record_id')->unsigned()->nullable();
            $table->bigInteger('user_id_creator')->unsigned()->nullable();
            $table->bigInteger('user_id_modifier')->unsigned()->nullable();
            $table->softDeletes();
            $table->rememberToken();            
            $table->timestamp('created_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->datetime('updated_at')->nullable();
            $table->foreign('record_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('user_id_creator')->references('id')->on('users')->onDelete('set null');
            $table->foreign('user_id_modifier')->references('id')->on('users')->onDelete('set null');
            $table->datetime('updated_at')->default(DB::raw('NULL ON UPDATE CURRENT_TIMESTAMP'))->change();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}
