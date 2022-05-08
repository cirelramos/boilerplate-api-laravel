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
        Schema::create('players', function (Blueprint $table) {
            $table->increments('id_player');
            $table->string('name', 150)->comment('Name Player');
            $table->string('url', 150)->comment('Url player');
            $table->string('photo', 250)->comment('Photo player');
            $table->integer('rank')->comment('1-10, where 1 is bad and 10 excellent')->default(0);
            $table->integer('active')->comment("0=hasn't contract, 1=has contract")->default(0);
            $table->integer('renew')->comment("0=hasn't to renew contract, 1=has to renew contract")
                ->default(0);
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
        Schema::dropIfExists('players');
    }
};
