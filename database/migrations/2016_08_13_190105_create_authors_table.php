<?php

use Illuminate\Database\Schema\Blueprint;

use Illuminate\Database\Migrations\Migration;


class CreateAuthorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('authors', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->string('name');
            $table->longText("description")->nullable();
            $table->bigInteger('user_id_creator')->unsigned()->nullable();
            $table->bigInteger('user_id_modifier')->unsigned()->nullable();
            $table->bigInteger('record_id')->unsigned()->nullable();            
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable()->default(DB::raw('NULL ON UPDATE CURRENT_TIMESTAMP'));
            $table->softDeletes();
            $table->foreign('user_id_creator')->references('id')->on('users')->onDelete('set null');
            $table->foreign('user_id_modifier')->references('id')->on('users')->onDelete('set null');
            $table->foreign('record_id')->references('id')->on('authors')->onDelete('cascade');
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
        Schema::drop('authors');
    }
}
