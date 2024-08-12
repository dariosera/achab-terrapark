<?php 

$permalinks = [];
$out = [];

foreach ($d["permalinks"] as &$pl) {
    $permalinks[] = $this->db->con->quote($pl);

    $out[$pl] = [
        "permalink" => $pl,
        "isFavorite" => false,
    ];

}
$permalinks = implode(",",$permalinks);

$contents = $this->db->sql_select("SELECT permalink FROM ua_favorites WHERE permalink IN ($permalinks) AND IDutente = ?", $this->user["IDutente"]);

foreach ($contents as &$c) {
    $out[$c["permalink"]]["isFavorite"] = true;
}

return array_values($out);