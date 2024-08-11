<?php

$themes = $this->db->sql_select("SELECT themeID, title, description FROM ct_themes ORDER BY title");

foreach ($themes as $i=>$l) {
    $themes[$i]["url"] = $this->run("public/safeUrlString",["input" => $l["title"]])["output"];
}

return [
    "themes" => $themes,
    "topics" => $this->db->sql_select("SELECT topicID, themeID, title FROM ct_topics"),
    "typologies" => $this->db->sql_select("SELECT typologyID, description, icon FROM ct_typologies"),
    "languages" => $this->db->sql_select("SELECT * FROM tr_languages"),
];