<?php


use Illuminate\Database\Capsule\Manager as Capsule;

Capsule::schema()->create('wi_widgets', function ($table) {
    $table->increments("widgetID");

    $table->integer("projectID")->unsigned();
    $table->foreign("projectID")->references("projectID")->on("pr_projects");

    $table->string("title");
    $table->string("authorized_domains");
    $table->text("config");



    $table->timestamps();
});

