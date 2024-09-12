<?php

$this->db->delete("ct_content_authors",[
    "permalink" => $d["permalink"],
    "AND" => [
        "authorID" => $d["authorID"]
    ]
]);

return $this->run("content/getAuthors", ["permalink" => $d["permalink"]]);