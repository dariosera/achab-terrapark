<?php


foreach ($d["contentList"] as &$content) {
    $this->db->insertInto("co_related_contents",[
        "course" => $d["course"],
        "permalink" => $content,
    ]);
}

return ["ok" => true];