<?php

$tmp_domain = "project-".bin2hex(random_bytes(5)).".terrapark.it";

$this->db->insertInto("pr_projects",[
    "customerID" => $d["customerID"],
    "title" => $d["title"],
    "domain" => $tmp_domain,
]);

return ["ok" => true];