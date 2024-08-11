<?php

$this->db->delete("pr_visible_contents",[
    "contentID" => $d["contentID"],
    "AND" => [
        "projectID" => $d["projectID"],
    ]
]);

return $this->run("project/getVisibleContents",["projectID" => $d["projectID"]]);