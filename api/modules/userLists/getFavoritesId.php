<?php

$lista = $this->db->sql_select("SELECT * FROM ul_liste WHERE IDutente = ? AND favorites = 1",$this->user["IDutente"]);
if (count($lista) === 0) {
    $IDlista = $this->db->insertInto("ul_liste",[
        "IDutente" => $this->user["IDutente"],
        "favorites" => "1",
        "titolo" => null,
    ]);
} else {
    $IDlista = $lista[0]["IDlista"];
}

return $IDlista;