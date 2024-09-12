<?php


use Illuminate\Database\Capsule\Manager as Capsule;


Capsule::schema()->table('ct_authors', function ($table) {
    $table->text('bibliography')->nullable();
});