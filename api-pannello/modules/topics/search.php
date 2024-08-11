<?php




$exact_match = $this->db->sql_select("SELECT topicID as id, title as text FROM ct_topics WHERE topicID = ?",$d["s"]);

if (count($exact_match) === 1) {
    return $exact_match;
} else {
    $s = "%".$d["s"]."%";

    if (isset($d["themeID"])) {
        return $this->db->sql_select("SELECT topicID as id, title as text FROM ct_topics WHERE title LIKE ? AND themeID = ?",$s, $d["themeID"]);
    } else {
        return $this->db->sql_select("SELECT topicID as id, title as text FROM ct_topics WHERE title LIKE ?",$s);
    }
}