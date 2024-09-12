<?php

if (isset($d["new"]) && $d["new"] === true) {
    $authorID = $this->db->insertInto("ct_authors",[
        "name" => $d["name"],
        "surname" => $d["surname"],
        "role" => $d["role"],
        "bio" => $d["bio"],
        "picture" => !isset($d["picture"]) ? null : $d["picture"],
    ]);
} else {
    $authorID = $d["authorID"];

    $this->db->update("ct_authors",[
        "name" => $d["name"],
        "surname" => $d["surname"],
        "role" => $d["role"],
        "bio" => $d["bio"],
        "picture" => !isset($d["picture"]) ? null : $d["picture"],
    ],["authorID" => $d["authorID"]]);
}

$bibliography_clean = preg_replace('/\s*[a-zA-Z\-]+="[^"]*"/i', '', $d["bibliography"]);
$this->db->update("ct_authors",[
    "bibliography" => $bibliography_clean,
], ["authorID" => $authorID],  true);

return ["ok" => true];