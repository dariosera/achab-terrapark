<?php


use Illuminate\Database\Capsule\Manager as Capsule;


Capsule::schema()->create('ct_content_authors', function ($table) {
    $table->integer("authorID")->unsigned();
    $table->foreign("authorID")->references("authorID")->on("ct_authors");

    $table->string("permalink");
    $table->foreign("permalink")->references("permalink")->on("ct_contents");

    $table->primary(["authorID","permalink"]);

    $table->timestamps();
});