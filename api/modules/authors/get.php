<?php

$list = $this->db->sql_select("SELECT authorID, name, surname, role, s3_key FROM ct_authors LEFT JOIN up_files ON ct_authors.picture = up_files.file_id");

foreach ($list as $i=>$l) {
    $list[$i]["picture_url"] = $this->config["s3"]["origin_endpoint"] . "/" . $this->config["s3"]["prefix"] . "/" . $list[$i]["s3_key"];
}


return $list;