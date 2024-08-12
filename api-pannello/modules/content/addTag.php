<?php

$this->db->insertInto("ct_content_tags",[
    "permalink" => $d["permalink"],
    "tagID" => $d["tagID"],
]);

return $this->run("content/getTags", ["permalink" => $d["permalink"]]);