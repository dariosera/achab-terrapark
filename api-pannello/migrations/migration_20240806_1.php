<?php


use Illuminate\Database\Capsule\Manager as Capsule;

Capsule::schema()->create('ua_opened_contents', function ($table) {
    
    $table->integer("IDutente")->unsigned();
    $table->foreign("IDutente")->references("IDutente")->on("aa_utenti");

    $table->string("permalink");
    $table->foreign("permalink")->references("permalink")->on("ct_contents");

    $table->string("course_permalink");
    $table->foreign("course_permalink")->references("permalink")->on("ct_contents");

    $table->primary(["IDutente","permalink"]);
    $table->timestamps();

});