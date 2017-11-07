<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTastesTable extends Migration
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
        Schema::create('tastes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('action_type');  // like the subject or dislike the subject.
            $table->integer('tasted_id');   // the subject id being tasted.
            $table->string('tasted_type');  // the subject type being tasted.
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
        Schema::dropIfExists('tastes');
    }
}
