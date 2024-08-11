<?php

$id = $this->db->insertInto("ct_tags",[
    "description" => $d["text"],
]);

return ["id" => $id, "text" => $d["text"]];