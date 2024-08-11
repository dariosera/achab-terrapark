<?php

$exact_match = $this->db->sql_select("SELECT authorID as id, surname as text FROM ct_authors WHERE authorID = ?",$d["s"]);

if (count($exact_match) === 1) {
    return $exact_match;
} else {
    $s = "%".$d["s"]."%";
    return$this->db->sql_select("SELECT authorID as id, surname as text FROM ct_authors WHERE surname LIKE ? OR name LIKE ?",$s,$s);
}