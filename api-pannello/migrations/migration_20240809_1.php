<?php


use Illuminate\Database\Capsule\Manager as Capsule;

Capsule::schema()->create('it_regioni', function ($table) {
    $table->increments("IDregione");
    $table->string("nome");
    $table->float("latitudine");
    $table->float("longitudine");
});

Capsule::schema()->create('it_province', function ($table) {
    $table->increments("IDprovincia");
    $table->integer("IDregione")->unsigned();
    $table->string("nome");
    $table->string("sigla_automobilistica");
    $table->float("latitudine");
    $table->float("longitudine");
    $table->foreign("IDregione")->references("IDregione")->on("it_regioni");
});

Capsule::schema()->create('it_comuni', function ($table) {
    $table->increments("IDcomune");
    $table->integer("IDregione")->unsigned();
    $table->integer("IDprovincia")->unsigned();
    $table->tinyInteger("capoluogo")->default(0);
    $table->string("codice_catastale");
    $table->string("nome");
    $table->float("latitudine");
    $table->float("longitudine");

    $table->foreign("IDprovincia")->references("IDprovincia")->on("it_province");
    $table->foreign("IDregione")->references("IDregione")->on("it_regioni");
});

Capsule::schema()->create('aa_informazioni_extra', function ($table) {
    $table->integer("IDutente");
    $table->foreign("IDutente")->references("IDutente")->on("aa_utenti");

    $table->integer("comune")->nullable();
    $table->foreign("comune")->references("IDcomune")->on("it_comuni");

    $table->string("anno_nascita", 4)->nullable();

    $table->string("professione")->nullable();
    
    $table->text("indirizzo")->nullable();

    $table->string("codice_fiscale")->nullable();

    $table->primary(["IDutente"]);

    $table->timestamps();
});