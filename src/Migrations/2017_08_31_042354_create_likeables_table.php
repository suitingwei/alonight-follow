<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLikeablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * A user may like many things, posts, comments, pictures,videos, so this likes table should be polymorphic.
         */
        Schema::create('likeables', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('likeable_id');
            $table->string('likeable_type');
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
        Schema::dropIfExists('likes');
    }
}
