<?php

use Illuminate\Database\Capsule\Manager as Capsule;

Capsule::schema()->create('co_related_contents', function ($table) {

    $table->string("course");
    $table->foreign("course")->references("permalink")->on("ct_contents");

    $table->string("permalink");
    $table->foreign("permalink")->references("permalink")->on("ct_contents");

    $table->primary(["course","permalink"]);
    

    $table->timestamps();
});