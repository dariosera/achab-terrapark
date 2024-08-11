<?php 

$permalinks = [];

foreach ($d["permalinks"] as &$pl) {
    $permalinks[] = $this->db->con->quote($pl);
}
$permalinks = implode(",",$permalinks);

$contents = $this->db->sql_select("SELECT permalink FROM ua_favorites WHERE permalink IN ($permalinks) AND IDutente = ?", $this->user["IDutente"]);


$out = [];

foreach ($contents as &$c) {
    $out[] = [
        "permalink" => $c["permalink"],
        "isFavorite" => true,
    ];
}

return $out;