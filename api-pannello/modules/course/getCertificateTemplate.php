<?php

$cert = $this->db->sql_select("SELECT * FROM ce_templates WHERE course = ?",$d["course"]);

if (count($cert) === 1) {
    return $this->run("core/files/getFile",["file_id" => $cert[0]["template"]]);
} else {
    return [];
}