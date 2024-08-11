<?php

$exact_match = $this->db->sql_select("SELECT iso_639_1 as id, description as text FROM tr_languages WHERE iso_639_1 = ?",$d["s"]);

if (count($exact_match) === 1) {
    return $exact_match;
} else {
    $s = "%".$d["s"]."%";
    return $this->db->sql_select("SELECT iso_639_1 as id, description as text FROM tr_languages WHERE description LIKE ?",$s);
}
