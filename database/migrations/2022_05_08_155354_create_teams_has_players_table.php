<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teams_has_players', function (Blueprint $table) {
            $table->integer('id_team')->unsigned()->comment('relation with table teams by id_team ');
            $table->foreign('id_team')->references('id_team')->on('teams')->onUpdate('cascade');
            $table->integer('id_player')->unsigned()->comment('relation with table players by id_player ');
            $table->foreign('id_player')->references('id_player')->on('players')->onUpdate('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('teams_has_players');
    }
};
