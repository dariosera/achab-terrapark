<?php

if (isset($d["new"]) && $d["new"] === true) {
    $this->db->insertInto("ct_authors",[
        "name" => $d["name"],
        "surname" => $d["surname"],
        "role" => $d["role"],
        "bio" => $d["bio"],
        "picture" => !isset($d["picture"]) ? null : $d["picture"],
    ]);
} else {
    $this->db->update("ct_authors",[
        "name" => $d["name"],
        "surname" => $d["surname"],
        "role" => $d["role"],
        "bio" => $d["bio"],
        "picture" => !isset($d["picture"]) ? null : $d["picture"],
    ],["authorID" => $d["authorID"]]);
}

return ["ok" => true];