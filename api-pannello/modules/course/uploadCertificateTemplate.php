<?php

$this->db->delete("ce_templates", ["course" => $d["data"]["course"]]);

$this->db->insertInto("ce_templates",[
    "course" => $d["data"]["course"],
    "template" =>  $d["files"][0]["internal_id"],
]);

return ["ok" => true];
