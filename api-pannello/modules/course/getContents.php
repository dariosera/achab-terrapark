<?php



$list = $this->db->sql_select("SELECT ct_contents.contentID, permalink, ct_contents.title, language, ct_topics.title AS topic, ct_themes.title AS theme, standalone, ct_typologies.description AS typology, draft, customer, image, ct_contents.description AS description FROM co_course_contents JOIN ct_contents ON ct_contents.contentID = co_course_contents.contentID LEFT JOIN ct_topics ON ct_topics.topicID = ct_contents.topicID LEFT JOIN ct_themes ON ct_topics.themeID = ct_themes.themeID LEFT JOIN ct_typologies ON ct_contents.typologyID = ct_typologies.typologyID LEFT JOIN cu_customers ON cu_customers.customerID = ct_contents.customerID WHERE courseID = ? ORDER BY customOrder", $d["courseID"]);

foreach ($list as $i=>$l) {
    if ($list[$i]["image"] !== null) {
        $list[$i]["image_url"] = $this->config["s3"]["origin_endpoint"] . "/" . $this->config["s3"]["prefix"] . "/c/160x90/" . $list[$i]["permalink"] . ".jpg";
    }
}

return $list;