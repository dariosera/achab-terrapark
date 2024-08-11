<?php


use Illuminate\Database\Capsule\Manager as Capsule;


Capsule::schema()->create('tr_languages', function ($table) {
    $table->string("iso_639_1",2);
    $table->primary("iso_639_1");
    $table->string("iso_639_2",3)->unique(); 
    $table->string("description");
    $table->timestamps();
});

Capsule::table("tr_languages")->insert([
    "iso_639_1" => "it",
    "iso_639_2" => "ita",
    "description" => "Italiano"
]);

Capsule::table("tr_languages")->insert([
    "iso_639_1" => "en",
    "iso_639_2" => "eng",
    "description" => "English"
]);

Capsule::schema()->create('ct_themes', function ($table) {
    $table->increments("themeID");
    $table->string("title");
    $table->text("description")->nullable();
    $table->text("long_description")->nullable();
    
    $table->integer("icon")->unsigned();
    $table->foreign("icon")->references("file_id")->on("up_files")->onDelete("cascade");

    $table->timestamps();
});


Capsule::schema()->create('ct_topics', function ($table) {
    $table->increments("topicID");
    $table->integer("themeID")->unsigned();
    $table->foreign("themeID")->references("themeID")->on("ct_themes");
    $table->string("title");
    $table->text("description")->nullable();
    $table->timestamps();
});

Capsule::schema()->create('ct_authors', function ($table) {
    $table->increments("authorID");
    $table->string("name");
    $table->string("surname");
    $table->string("role");
    $table->integer("picture")->unsigned();
    $table->foreign("picture")->references("file_id")->on("up_files")->onDelete("cascade");
    $table->text("bio")->nullable();



    $table->timestamps();
});

Capsule::schema()->create('ct_typologies', function ($table) {
    $table->increments("typologyID");
    $table->string("description");
    $table->string("icon");
    $table->timestamps();
});

Capsule::schema()->create('ct_contents', function ($table) {
    $table->increments("contentID");
    $table->string("permalink")->unique();
    $table->string("title")->nullable();
    $table->text("description")->nullable();

    $table->string("language",2)->nullable();
    $table->foreign("language")->references("iso_639_1")->on("tr_languages");

    $table->integer("topicID")->unsigned()->nullable();
    $table->foreign("topicID")->references("topicID")->on("ct_topics");

    $table->tinyInteger("standalone")->default(0);
    $table->tinyInteger("isCourse")->default(0);

    $table->integer("image")->unsigned()->nullable();
    $table->foreign("image")->references("file_id")->on("up_files")->onDelete("cascade");

    $table->text("string")->default('{}');

    $table->text("string")->default('{}');

    $table->integer("typologyID")->unsigned()->nullable();
    $table->foreign("typologyID")->references("typologyID")->on("ct_typologies");

    $table->integer("authorID")->unsigned()->nullable();
    $table->foreign("authorID")->references("authorID")->on("ct_authors");

    $table->integer("customerID")->unsigned()->nullable();
    $table->foreign("customerID")->references("customerID")->on("cu_customers");

    $table->tinyInteger("draft")->default(1);
    $table->tinyInteger("deleted")->default(0);

    $table->timestamps();
});

Capsule::schema()->create('ct_tags', function ($table) {
    $table->increments("tagID");
    $table->string("description");
    $table->timestamps();
});


Capsule::schema()->create('ct_content_tags', function ($table) {
    $table->integer("tagID")->unsigned();
    $table->foreign("tagID")->references("tagID")->on("ct_tags")->onDelete("cascade");
    $table->integer("contentID")->unsigned();
    $table->foreign("contentID")->references("contentID")->on("ct_contents")->onDelete("cascade");
    $table->primary(["tagID","contentID"]);
    $table->timestamps();
});

