<?php

$exact_match = $this->db->sql_select("SELECT projectID as id, title as text FROM pr_projects WHERE projectID = ?",$d["s"]);

if (count($exact_match) === 1) {
    return $exact_match;
} else {
    $s = "%".$d["s"]."%";
    return $this->db->sql_select("SELECT projectID as id, title as text FROM pr_projects WHERE title LIKE ?",$s);
}
