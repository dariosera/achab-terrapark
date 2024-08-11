<?php


foreach ($d["newOrder"] as &$content) {
    $this->db->update("co_course_contents",[
        "customOrder" => $content["customOrder"],
    ],[
        "contentID" => $content["contentID"],
        "AND" => [
            "courseID" => $d["courseID"],
        ]
    ]);
}

return ["ok" => true];