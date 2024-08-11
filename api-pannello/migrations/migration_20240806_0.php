<?php


use Illuminate\Database\Capsule\Manager as Capsule;

Capsule::schema()->create('ua_media_position', function ($table) {
    
    $table->integer("IDutente")->unsigned();
    $table->string("permalink");
    $table->primary(["IDutente","permalink"]);
    $table->integer("position")->default(0);
    $table->timestamps();

});