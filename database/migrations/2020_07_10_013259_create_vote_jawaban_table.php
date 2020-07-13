<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVoteJawabanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vote_jawaban', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('jawaban_id');
            $table->unsignedBigInteger('penjawab_id');
            $table->integer('value');
            $table->integer('reputasi')->default(0);
            $table->foreign('user_id')->references('id')->on('users')->onDelete("cascade");
            $table->foreign('jawaban_id')->references('id')->on('jawaban')->onDelete("cascade");
            $table->foreign('penjawab_id')->references('user_id')->on('jawaban')->onDelete("cascade");
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
        Schema::dropIfExists('vote_jawaban');
    }
}
