<?php

use Illuminate\Database\Schema\Blueprint;

use Illuminate\Database\Migrations\Migration;


class CreateBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->string('isbn',13);
            $table->string('name');
            $table->integer('count')->default(0);
            $table->boolean('available')->default(FALSE);
            $table->longText('description')->nullable();
            $table->string('format',30)->nullable();
            $table->integer('pages')->unsigned()->default(0);
            $table->date('date_published')->nullable();
            $table->bigInteger('publisher_id')->unsigned()->nullable();
            $table->bigInteger('user_id_creator')->unsigned()->nullable();
            $table->bigInteger('user_id_modifier')->unsigned()->nullable();
            $table->bigInteger('record_id')->unsigned()->nullable();
            $table->softDeletes();            
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable()->default(DB::raw('NULL ON UPDATE CURRENT_TIMESTAMP'));
            $table->foreign('publisher_id')->references('id')->on('publishers')->onDelete('set null');
            $table->foreign('record_id')->references('id')->on('books')->onDelete('set null');
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
        Schema::drop('books');
    }
}
