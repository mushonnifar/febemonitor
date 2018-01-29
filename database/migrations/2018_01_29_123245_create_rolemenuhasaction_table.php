<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolemenuhasactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rolemenuhasaction', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('rolemenu_id')->unsigned();
            $table->integer('action_id')->unsigned();
            $table->integer('created_by');
            $table->integer('deleted_by');
            $table->integer('updated_by');
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('rolemenu_id')->references('id')->on('rolehasmenu');
            $table->foreign('action_id')->references('id')->on('actions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rolemenuhasaction');
    }
}
