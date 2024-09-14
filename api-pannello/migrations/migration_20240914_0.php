<?php


use Illuminate\Database\Capsule\Manager as Capsule;

Capsule::schema()->create('qz_quiz_responses', function ($table) {

    $table->increments("responseID");
    
    $table->integer("IDutente")->unsigned();
    $table->foreign("IDutente")->references("IDutente")->on("aa_utenti");

    $table->string("permalink");
    $table->foreign("permalink")->references("permalink")->on("ct_contents");

    $table->text("correct_answers");

    $table->text("user_answers")->nullable();

    $table->integer("score")->nullable();

    $table->timestamps();

});