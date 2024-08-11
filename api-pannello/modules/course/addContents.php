<?php


// Conto contenuti esistenti
$c = $this->db->sql_select("SELECT * FROM co_course_contents WHERE courseID = ?",$d["courseID"]);

$i = count($c) + 1;

foreach ($d["contentList"] as &$content) {
    $this->db->insertInto("co_course_contents",[
        "courseID" => $d["courseID"],
        "contentID" => $content,
        "customOrder" => $i++
    ]);
}

return ["ok" => true];