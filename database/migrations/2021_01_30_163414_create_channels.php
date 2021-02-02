<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChannels extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('channels', function (Blueprint $table) {
            $table->id()->unique();
            $table->string('name');
            $table->string('slug');
            $table->timestamp('stream_created')->nullable();
            $table->boolean('live')->default(false);
            $table->string('title')->nullable();
            $table->integer('game_id')->default(0);
            $table->string('avatar')->nullable();
            $table->string('thumbnail')->nullable();
            $table->bigInteger('views')->default(0);
            $table->bigInteger('viewers')->default(0);
            $table->boolean('partner')->default(false);
            $table->text('tags')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('channels');
    }
}
