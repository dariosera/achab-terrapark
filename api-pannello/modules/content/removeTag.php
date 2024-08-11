<?php

$this->db->delete("ct_content_tags",[
    "contentID" => $d["contentID"],
    "AND" => [
        "tagID" => $d["tagID"]
    ]
]);

return $this->run("content/getTags", ["contentID" => $d["contentID"]]);