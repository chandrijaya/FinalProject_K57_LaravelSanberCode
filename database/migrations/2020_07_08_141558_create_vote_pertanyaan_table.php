<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVotePertanyaanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vote_pertanyaan', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('pertanyaan_id');
            $table->unsignedBigInteger('penanya_id');
            $table->integer('value');
            $table->integer('reputasi')->default(0);
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('pertanyaan_id')->references('id')->on('pertanyaan')->onDelete("cascade");
            $table->foreign('penanya_id')->references('user_id')->on('pertanyaan')->onDelete("cascade");
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
        Schema::dropIfExists('vote_pertanyaan');
    }
}
