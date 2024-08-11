<?php

$regioni = json_decode(file_get_contents("https://raw.githubusercontent.com/napolux/italia/master/json/regioni.json"), true);

foreach ($regioni as &$r) {
    $this->db->insertInto("it_regioni",[
        "IDregione" => $r["id"],
        "nome" => $r["nome"],
        "latitudine" => $r["latitudine"],
        "longitudine" => $r["longitudine"],
    ]);
}

unset($regioni);

$province = json_decode(file_get_contents("https://raw.githubusercontent.com/napolux/italia/master/json/province.json"), true);


foreach ($province as &$p) {
    $this->db->insertInto("it_province",[
        "IDprovincia" => $p["id"],
        "IDregione" => $p["id_regione"],
        "nome" => $p["nome"],
        "sigla_automobilistica" => $p["sigla_automobilistica"],
        "latitudine" => $p["latitudine"],
        "longitudine" => $p["longitudine"],
    ]);
}

unset($province);


$comuni = json_decode(file_get_contents("https://raw.githubusercontent.com/napolux/italia/master/json/comuni.json"), true);

foreach ($comuni as &$c) {

    if ($c["capoluogo_provincia"] === false) {
        $cp = 0;
    } else {
        $cp = 1;
    }

    $this->db->insertInto("it_comuni",[
        "IDcomune" => $c["id"],
        "IDregione" => $c["id_regione"],
        "IDprovincia" => $c["id_provincia"],
        "nome" => $c["nome"],
        "capoluogo" => $cp,
        "codice_catastale" => $c["codice_catastale"],
        "latitudine" => $c["latitudine"],
        "longitudine" => $c["longitudine"],
    ]);
}

unset($comuni);


return ["ok" => true];
