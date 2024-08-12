<?php

$list = $this->db->sql_select("SELECT themeID, title, description FROM ct_themes ORDER BY title");

foreach ($list as $i=>$l) {
    $list[$i]["url"] = $this->run("frontend/public/safeUrlString",["input" => $l["title"]])["output"];
}

return $list;