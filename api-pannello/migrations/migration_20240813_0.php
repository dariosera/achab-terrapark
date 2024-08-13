<?php


use Illuminate\Database\Capsule\Manager as Capsule;

$IDgruppo = Capsule::table("aa_gruppi")->insertGetId([
    "gruppo" => "Achab"
]);

$permessi = [
    "default.authors",
    "default.content",
    "default.contents",
    "default.course",
    "default.courses",
    "default.customer",
    "default.customers",
    "default.homepage",
    "default.languages",
    "default.project",
    "default.projects",
    "default.tags",
    "default.themes",
    "default.topics",
    "default.typologies",
    "default.vimeo",
    "default.widgets.get",
    "default.widgets.single",
    "default.content",
    "core.files",
    "core.s3"
];

foreach ($permessi as &$p) {
    Capsule::table("zz_permessi")->insert([
        "task" => $p,
        "IDgruppo" => $IDgruppo,
        "permesso" => "1"
    ]);
}

