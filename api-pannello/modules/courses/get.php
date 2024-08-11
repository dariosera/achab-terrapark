<?php

$list = $this->db->sql_select("SELECT permalink, ct_contents.title, ct_themes.title AS theme, ct_topics.title AS topic, ct_typologies.description AS typology, language, draft, customer FROM ct_contents LEFT JOIN ct_topics ON ct_topics.topicID = ct_contents.topicID LEFT JOIN ct_themes ON ct_topics.themeID = ct_themes.themeID LEFT JOIN ct_typologies ON ct_contents.typologyID = ct_typologies.typologyID LEFT JOIN cu_customers ON cu_customers.customerID = ct_contents.customerID WHERE deleted = 0 AND isCourse = 1");

foreach ($list as $i=>$l) {
    //$list[$i]["standalone"] = $list[$i]["standalone"] == '0' ? false : true;
    $list[$i]["visible"] = $list[$i]["draft"] == '0' ? true : false;
}

return $list;