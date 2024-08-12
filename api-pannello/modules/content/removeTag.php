<?php

$this->db->delete("ct_content_tags",[
    "permalink" => $d["permalink"],
    "AND" => [
        "tagID" => $d["tagID"]
    ]
]);

return $this->run("content/getTags", ["permalink" => $d["permalink"]]);