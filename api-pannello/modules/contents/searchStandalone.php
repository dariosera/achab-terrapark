<?php

$list = $this->db->sql_select("SELECT ct_contents.contentID, permalink, ct_contents.title, language, ct_topics.title AS topic, ct_themes.title AS theme, standalone, ct_typologies.description AS typology, draft, customer FROM ct_contents LEFT JOIN ct_topics ON ct_topics.topicID = ct_contents.topicID LEFT JOIN ct_themes ON ct_topics.themeID = ct_themes.themeID LEFT JOIN ct_typologies ON ct_contents.typologyID = ct_typologies.typologyID LEFT JOIN cu_customers ON cu_customers.customerID = ct_contents.customerID WHERE standalone = 1 AND deleted = 0 AND isCourse = 0 AND (ct_contents.title LIKE ? OR permalink = ?)", "%".$d["s"]."%", $d["s"]);

foreach ($list as $i=>$l) {
    $list[$i]["standalone"] = $list[$i]["standalone"] == '0' ? false : true;
    $list[$i]["visible"] = $list[$i]["draft"] == '0' ? true : false;
}

return $list;