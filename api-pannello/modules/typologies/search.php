<?php

$exact_match = $this->db->sql_select("SELECT typologyID as id, description as text FROM ct_typologies WHERE typologyID = ?",$d["s"]);

if (count($exact_match) === 1) {
    return $exact_match;
} else {
    $s = "%".$d["s"]."%";
    return $this->db->sql_select("SELECT typologyID as id, description as text FROM ct_typologies WHERE description LIKE ?",$s);
}