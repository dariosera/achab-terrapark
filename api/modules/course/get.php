<?php

/*

possibili filtri
corsi / contenuti singoli
*/

$required_fields = ["title"];
$constraints = [];
foreach ($required_fields as &$field) {
    $constraints[] = "$field IS NOT NULL";
}
$constraints[] = "draft = 0";
$constraints[] = "deleted = 0";

$constraints_sql = implode(" AND ",$constraints);

$list = $this->db->sql_select("SELECT contentID, permalink, title, description, language, themeID, topicID, typologyID, isCourse, image, meta FROM ct_contents WHERE permalink = ? AND isCourse = 1 AND ($constraints_sql)", $d["permalink"]);

if (count($list) !== 1) {
    return ["error" => "corso_inesistente"];
}

foreach ($list as $i=>$l) {
    if ($list[$i]["image"] !== null) {
        $list[$i]["image"] = $this->config["s3"]["origin_endpoint"] . "/" . $this->config["s3"]["prefix"] . "/c/640x360/" . $l["permalink"] . ".jpg";
    }
}

// Cerco i contenuti associati a quel corso

$contents = $this->db->sql_select("SELECT ct_contents.permalink, title, description, language, topicID, typologyID, isCourse, image, meta, media, COUNT(ua_opened_contents.IDutente) AS seen  FROM co_course_contents JOIN ct_contents ON co_course_contents.contentID = ct_contents.contentID LEFT JOIN ua_opened_contents ON ct_contents.permalink = ua_opened_contents.permalink AND  ua_opened_contents.IDutente = ? WHERE co_course_contents.courseID = ? AND ($constraints_sql) GROUP BY ct_contents.permalink ORDER BY customOrder", $this->user["IDutente"],  $list[0]["contentID"]);

foreach ($contents as $i=>$c) {
    $contents[$i]["media"] = $this->run("frontend/content/getMedia",["permalink" => $c["permalink"], "media" => $c["media"]]);
    $contents[$i]["meta"] = json_decode($contents[$i]["meta"],true);
    $contents[$i]["image"] = $this->config["s3"]["origin_endpoint"] . "/" . $this->config["s3"]["prefix"] . "/c/640x360/" . $c["permalink"] . ".jpg";


}

$list[0]["authors"] = $this->run("frontend/content/getAuthors",["permalink" => $d["permalink"]]);

$list[0]["contents"] = $contents;

return $list[0];