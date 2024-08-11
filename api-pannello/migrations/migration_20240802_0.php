<?php


use Illuminate\Database\Capsule\Manager as Capsule;


Capsule::schema()->create('hp_slides', function ($table) {  
    $table->increments("slideID");

    $table->string("title", 90);
    $table->string("content",160);
    $table->string("link_text",30);
    $table->string("link_href");

    $table->integer("customerID")->unsigned()->nullable();
    $table->foreign("customerID")->references("customerID")->on("cu_customers");

    $table->integer("image")->unsigned()->nullable();
    $table->foreign("image")->references("file_id")->on("up_files")->onDelete("cascade");

    $table->timestamps();
});

Capsule::schema()->create('pr_visible_slides', function ($table) {
    
    $table->integer("slideID")->unsigned();
    $table->foreign("slideID")->references("slideID")->on("hp_slides");

    $table->integer("projectID")->unsigned();
    $table->foreign("projectID")->references("projectID")->on("pr_projects");

    $table->primary(["slideID","projectID"]);

    $table->tinyInteger("customOrder");

    $table->timestamps();
});