<?php

$this->db->insertInto("ct_content_tags",[
    "contentID" => $d["contentID"],
    "tagID" => $d["tagID"],
]);

return $this->run("content/getTags", ["contentID" => $d["contentID"]]);