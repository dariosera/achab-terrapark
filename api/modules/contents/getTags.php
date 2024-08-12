<?php

$out = [];
foreach ($d["permalinks"] as &$permalink) {
    $out[] = [
        "permalink" => $permalink,
        "tags" => $this->db->sql_select("SELECT ct_tags.tagID, ct_tags.description FROM ct_content_tags JOIN ct_tags ON ct_content_tags.tagID = ct_tags.tagID WHERE permalink = ?", $permalink)
    ];
}


return $out;