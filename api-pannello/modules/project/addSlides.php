<?php

// Conto slides esistenti
$c = $this->db->sql_select("SELECT * FROM pr_visible_slides WHERE projectID = ?",$d["projectID"]);

$i = count($c) + 1;

foreach ($d["slides"] as &$slideID) {
    $this->db->insertInto("pr_visible_slides",[
        "projectID" => $d["projectID"],
        "slideID" => $slideID,
        "customOrder" => $i++,
    ]);
}


return ["ok" => true];