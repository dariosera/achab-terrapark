<?php


use Illuminate\Database\Capsule\Manager as Capsule;

Capsule::schema()->create('cu_customers', function ($table) {
    $table->increments("customerID");  
    $table->string("customer");
    $table->timestamps();
});

Capsule::schema()->create('pr_projects', function ($table) {
    $table->increments("projectID");  
    $table->integer("customerID")->unsigned();
    $table->foreign("customerID")->references("customerID")->on("cu_customers");
    $table->string("title");
    $table->date("start_date")->nullable();
    $table->date("end_date")->nullable();
    $table->string("domain")->unique();
    $table->text("theme");
    $table->string("cs_email")->nullable();
    $table->string("cs_phone")->nullable();
    $table->string("cs_hours")->nullable();
    $table->tinyInteger("archived")->default(0);
    $table->timestamps();
});

