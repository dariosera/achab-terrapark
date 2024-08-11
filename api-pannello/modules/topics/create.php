<?php

$id = $this->db->insertInto("ct_topics",[
    "title" => $d["text"],
    "themeID" => $d["customData"]["themeID"],
]);

return ["id" => $id, "text" => $d["text"]];