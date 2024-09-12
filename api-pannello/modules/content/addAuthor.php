<?php

$this->db->insertInto("ct_content_authors",[
    "permalink" => $d["permalink"],
    "authorID" => $d["authorID"],
]);

return $this->run("content/getAuthors", ["permalink" => $d["permalink"]]);