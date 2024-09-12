<?php

$pro = $this->run("frontend/public/projectInfo");

$themes = $this->db->sql_select("SELECT ct_themes.themeID, title, description FROM pr_visible_themes JOIN ct_themes ON pr_visible_themes.themeID = ct_themes.themeID WHERE projectID = ? ORDER BY title", $pro["projectID"]);

foreach ($themes as $i=>$l) {
    $themes[$i]["url"] = $this->run("frontend/public/safeUrlString",["input" => $l["title"]])["output"];
}

return [
    "themes" => $themes,
    "topics" => $this->db->sql_select("SELECT topicID, themeID, title FROM ct_topics"),
    "typologies" => $this->db->sql_select("SELECT typologyID, description, icon FROM ct_typologies"),
    "languages" => $this->db->sql_select("SELECT * FROM tr_languages"),
    "authors" => $this->db->sql_select("SELECT authorID, name, surname FROM ct_authors"),
];