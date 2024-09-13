<?php

$this->db->delete("ct_content_authors",[
    "permalink" => $d["permalink"],
    "AND" => [
        "authorID" => $d["authorID"]
    ]
]);

// Controllo se fa parte di un corso
$course = $this->db->sql_select("SELECT courses.permalink FROM co_course_contents JOIN ct_contents ON ct_contents.contentID = co_course_contents.contentID JOIN ct_contents AS courses ON courses.contentID = co_course_contents.courseID WHERE ct_contents.permalink = ?", $d["permalink"]);

if (count($course) === 1) {
   
$this->db->delete("ct_content_authors",[
    "permalink" => $course[0]["permalink"],
    "AND" => [
        "authorID" => $d["authorID"]
    ]
]);
}

return $this->run("content/getAuthors", ["permalink" => $d["permalink"]]);