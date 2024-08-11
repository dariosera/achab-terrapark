<?php


use Illuminate\Database\Capsule\Manager as Capsule;

Capsule::schema()->create('pr_visible_contents', function ($table) {
    $table->integer("contentID")->unsigned();
    $table->foreign("contentID")->references("contentID")->on("ct_contents")->onDelete("cascade");

    $table->integer("projectID")->unsigned();
    $table->foreign("projectID")->references("projectID")->on("pr_projects")->onDelete("cascade");

    $table->primary(["contentID","projectID"]);

    $table->timestamps();
});