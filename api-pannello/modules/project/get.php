<?php

$p = $this->db->sql_select("SELECT * FROM pr_projects WHERE projectID = ?",$d["projectID"]);

if (count($p) !== 1) {
    return ["error" => "Progetto non trovato"];
}

$p[0]["theme"] = json_decode($p[0]["theme"], true);
$p[0]["privacy"] = json_decode($p[0]["privacy"], true);


return $p;