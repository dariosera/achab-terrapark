<?php

$list = $this->db->sql_select("SELECT ct_authors.*, s3_key FROM ct_authors LEFT JOIN up_files ON ct_authors.picture = up_files.file_id WHERE authorID = ?", $d["authorID"]);

$list[0]["picture_url"] = $this->config["s3"]["origin_endpoint"] . "/" . $this->config["s3"]["prefix"] . "/" . $list[0]["s3_key"];


return $list;