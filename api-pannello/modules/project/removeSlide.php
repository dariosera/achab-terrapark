<?php

$this->db->delete("pr_visible_slides",[
    "slideID" => $d["slideID"],
    "AND" => [
        "projectID" => $d["projectID"]
    ]
]);

return ["ok" => true];