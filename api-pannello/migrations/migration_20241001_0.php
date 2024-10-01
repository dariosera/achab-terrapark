<?php


use Illuminate\Database\Capsule\Manager as Capsule;


Capsule::schema()->table('pr_projects', function ($table) {
    $table->text('privacy')->nullable();
});