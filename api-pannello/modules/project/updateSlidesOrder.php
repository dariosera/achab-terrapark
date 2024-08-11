<?php


foreach ($d["newOrder"] as &$content) {
    $this->db->update("pr_visible_slides",[
        "customOrder" => $content["customOrder"],
    ],[
        "slideID" => $content["slideID"],
        "AND" => [
            "projectID" => $d["projectID"],
        ]
    ]);
}

return ["ok" => true];