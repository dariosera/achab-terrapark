<?php

use Illuminate\Database\Capsule\Manager as Capsule;

Capsule::schema()->create('ce_templates', function ($table) {

    $table->string("course");
    $table->foreign("course")->references("permalink")->on("ct_contents");

    $table->integer("template")->unsigned()->nullable();
    $table->foreign("template")->references("file_id")->on("up_files")->onDelete("cascade");


    $table->primary(["course"]);
    

    $table->timestamps();
});