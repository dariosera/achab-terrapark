<?php

$exact_match = $this->db->sql_select("SELECT themeID as id, title as text FROM ct_themes WHERE themeID = ?",$d["s"]);

if (count($exact_match) === 1) {
    return $exact_match;
} else {
    $s = "%".$d["s"]."%";
    return $this->db->sql_select("SELECT themeID as id, title as text FROM ct_themes WHERE title LIKE ?",$s);
}