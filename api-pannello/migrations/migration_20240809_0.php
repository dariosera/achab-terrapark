<?php


use Illuminate\Database\Capsule\Manager as Capsule;

Capsule::schema()->create('pr_visible_themes', function ($table) {
    $table->integer("themeID")->unsigned();
    $table->foreign("themeID")->references("themeID")->on("ct_themes");

    $table->integer("projectID")->unsigned();
    $table->foreign("projectID")->references("projectID")->on("pr_projects")->onDelete("cascade");

    $table->primary(["themeID","projectID"]);

    $table->timestamps();
});