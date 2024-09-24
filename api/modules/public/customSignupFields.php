<?php

$pro = $this->run("frontend/public/projectInfo");

$cf = $this->db->sql_select("SELECT * FROM pr_custom_signup_fields WHERE projectID = ?", $pro["projectID"]);

foreach ($cf as $i=>$c) {
    $cf[$i]["data"] = json_decode($c["data"],true);
}

return $cf;