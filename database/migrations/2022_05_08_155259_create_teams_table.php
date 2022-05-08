<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teams', function (Blueprint $table) {
            $table->increments('id_team');
            $table->string('name', 150)->comment('Name Team');
            $table->string('url', 150)->comment('Url Team');
            $table->string('logo', 250)->comment('Logo Team')->nullable();
            $table->string('photo', 250)->comment('Photo Team')->nullable();
            $table->integer('rank')->comment('1-10, where 1 is bad and 10 excellent')->default(0);
            $table->integer('active')->comment("0=disable, 1=active")->default(0);
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
        Schema::dropIfExists('teams');
    }
};
