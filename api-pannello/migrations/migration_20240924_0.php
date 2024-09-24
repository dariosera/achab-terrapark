<?php


use Illuminate\Database\Capsule\Manager as Capsule;

Capsule::schema()->create('pr_custom_signup_fields', function ($table) {

    $table->increments("fieldID");

    $table->integer("projectID")->unsigned();
    $table->foreign("projectID")->references("projectID")->on("pr_projects");
    $table->text("data");
    $table->timestamps();

});

Capsule::schema()->create('pr_custom_signup_fields_responses', function ($table) {


    $table->integer("fieldID")->unsigned();
    $table->foreign("fieldID")->references("fieldID")->on("pr_custom_signup_fields");

    $table->integer("IDutente")->unsigned();
    $table->foreign("IDutente")->references("IDutente")->on("aa_utenti");

    $table->string("response")->nullable();

    $table->primary(["fieldID","IDutente"]);
    
    $table->timestamps();

});