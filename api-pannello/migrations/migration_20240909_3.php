<?php

use Illuminate\Database\Capsule\Manager as Capsule;

Capsule::schema()->create('ce_certificates', function ($table) {

    $table->increments("certificateID");

    $table->string("permalink");
    $table->foreign("permalink")->references("permalink")->on("ct_contents");

    $table->integer("IDutente")->unsigned();
    $table->foreign("IDutente")->references("IDutente")->on("aa_utenti");

    $table->timestamps();
});