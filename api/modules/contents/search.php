<?php

/*

possibili filtri
corsi / contenuti singoli
*/

$required_fields = ["title","description","image"];
$constraints = [];
foreach ($required_fields as &$field) {
    $constraints[] = "$field IS NOT NULL";
}
$constraints[] = "draft = 0";
$constraints[] = "deleted = 0";

$constraints_sql = implode(" AND ",$constraints);

if (!isset($d["target"])) {
    $d["target"] = "no_target";
}

$standard_fields = "contentID, ct_contents.permalink, title, description, language, topicID, themeID, typologyID, isCourse, image, meta";

switch ($d["target"]) {

    case "history":
        $query = "SELECT $standard_fields, ua_history.updated_at AS last_seen FROM ct_contents JOIN ua_history ON ua_history.permalink = ct_contents.permalink WHERE (isCourse = 1 OR standalone = 1) AND ($constraints_sql) AND ua_history.IDutente = ? ORDER BY last_seen DESC";
        $list = $this->db->sql_select($query, $this->user["IDutente"]);

        $list = array_map(function($x) { $x["last_seen"] = (new DateTime($x["last_seen"]))->format("c"); return $x; }, $list);

        break;

    case "favoritedStandalones":
        $query = "SELECT $standard_fields FROM ct_contents WHERE (standalone = 1) AND ($constraints_sql) AND permalink IN (SELECT permalink FROM ua_favorites WHERE IDutente = ?)";
        $list = $this->db->sql_select($query, $this->user["IDutente"]);
        break;

    case "favoritedCourses":
        $query = "SELECT $standard_fields FROM ct_contents WHERE (isCourse = 1) AND ($constraints_sql) AND permalink IN (SELECT permalink FROM ua_favorites WHERE IDutente = ?)";
        $list = $this->db->sql_select($query, $this->user["IDutente"]);
        break;

    case "myCourses":
        $query = "SELECT DISTINCT $standard_fields FROM ua_opened_contents JOIN ct_contents ON ua_opened_contents.course_permalink = ct_contents.permalink WHERE (isCourse = 1) AND ($constraints_sql) AND ua_opened_contents.IDutente = ?";
        $list = $this->db->sql_select($query, $this->user["IDutente"]);
        break;
    
    case "courses":
        $query = "SELECT $standard_fields FROM ct_contents WHERE isCourse = 1 AND ($constraints_sql)";
        $list = $this->db->sql_select($query);
        break;

    case "author":
        $query = "SELECT $standard_fields FROM ct_contents WHERE (isCourse = 1 OR standalone = 1) AND ($constraints_sql) AND ct_contents.permalink IN (SELECT permalink FROM ct_content_authors WHERE authorID = ?)";
        $list = $this->db->sql_select($query, $d["authorID"]);
        break;

    case "related":
        $query = "SELECT $standard_fields FROM co_related_contents JOIN ct_contents ON co_related_contents.permalink = ct_contents.permalink WHERE (standalone = 1) AND ($constraints_sql) AND co_related_contents.course = ?";
        $list = $this->db->sql_select($query, $d["course"]);
        break;

    case "latestContents":
        $query = "SELECT $standard_fields FROM ct_contents WHERE (standalone = 1) AND ($constraints_sql) ORDER BY ct_contents.contentID DESC LIMIT 10";
        $list = $this->db->sql_select($query);
        break;

    case "single":
        $query =  "SELECT $standard_fields FROM ct_contents WHERE (standalone = 1) AND ($constraints_sql) AND permalink = ?";
        $list = $this->db->sql_select($query, $d["permalink"]);
        break;

    default:
        $query = "SELECT $standard_fields FROM ct_contents WHERE (isCourse = 1 OR standalone = 1) AND ($constraints_sql)";
        $list = $this->db->sql_select($query);

}

foreach ($list as $i=>$l) {
    if ($list[$i]["image"] !== null) {
        $list[$i]["image"] = $this->config["s3"]["origin_endpoint"] . "/" . $this->config["s3"]["prefix"] . "/c/640x360/" . $l["permalink"] . ".jpg";
    }

    if ($l["isCourse"] == 1) {

        $course_contents = $this->db->sql_select("SELECT contentID FROM co_course_contents WHERE courseID = ?",  $l["contentID"]);
        $opened_contents = $this->db->sql_select("SELECT permalink FROM ua_opened_contents WHERE course_permalink = ? AND IDutente = ?",  $l["permalink"], $this->user["IDutente"]);

        if (count($course_contents) == 0) {
            $list[$i]["progress"] = 0;
        } else {
            $list[$i]["progress"] = count($opened_contents) / count($course_contents);
        }
        

    } else {
        $list[$i]["position"] = $this->run("frontend/userActions/getMediaPosition",["permalink" => $l["permalink"]])["position"];
    }

    $list[$i]["meta"] = json_decode($list[$i]["meta"], true);

    $list[$i]["authors"] = $this->run("frontend/content/getAuthors",["permalink" => $l["permalink"]]);

    unset($list[$i]["contentID"]);

}


return $list;