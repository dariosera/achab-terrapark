<?php

$this->db->delete("co_course_contents",[
    "courseID" => $d["courseID"],
    "AND" => [
        "contentID" => $d["contentID"],
    ]
]);

return ["ok" => true];