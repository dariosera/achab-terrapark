<?php


use Illuminate\Database\Capsule\Manager as Capsule;


Capsule::schema()->create('co_course_contents', function ($table) {
    $table->integer("contentID")->unsigned();
    $table->foreign("contentID")->references("contentID")->on("ct_contents");

    $table->integer("courseID")->unsigned();
    $table->foreign("courseID")->references("contentID")->on("ct_contents");

    $table->primary(["contentID","courseID"]);

    $table->integer("customOrder")->nullable();

    $table->tinyInteger("blocking")->default('0');

    $table->timestamps();

});