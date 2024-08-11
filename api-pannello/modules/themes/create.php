<?php

$id = $this->db->insertInto("ct_themes",[
    "title" => $d["text"],
]);

return ["id" => $id, "text" => $d["text"]];