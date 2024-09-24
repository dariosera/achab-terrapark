<?php

// Trovo il widget
$widget = $this->db->sql_select("SELECT * FROM wi_widgets WHERE widgetID = ?",$d["widgetID"]);

if (count($widget) !== 1) {
    return ["error" => "Widget configurato in modo errato"];
}


$required_fields = ["title","description","image"];
$constraints = [];
foreach ($required_fields as &$field) {
    $constraints[] = "$field IS NOT NULL";
}
$constraints[] = "draft = 0";
$constraints[] = "deleted = 0";

$constraints_sql = implode(" AND ",$constraints);

$standard_fields = "contentID, ct_contents.permalink, title, description, language, topicID, themeID, typologyID, isCourse, image, meta";

$config = json_decode($widget[0]["config"], true);

switch ($config["mode"]) {

    case "new":
        $query = "SELECT $standard_fields FROM ct_contents WHERE (standalone = 1) AND ($constraints_sql) ORDER BY ct_contents.contentID DESC LIMIT 10";
        $list = $this->db->sql_select($query);
        break;

    case "course":
        $permalink = $config["course_data"];
        $query = "SELECT $standard_fields FROM ct_contents WHERE ($constraints_sql) AND permalink = ? ORDER BY ct_contents.contentID DESC LIMIT 10";
        $list = $this->db->sql_select($query, $permalink);
        break;

    case "single":
        $permalink = $config["single_data"];
        $query = "SELECT $standard_fields FROM ct_contents WHERE ($constraints_sql) AND permalink = ? ORDER BY ct_contents.contentID DESC LIMIT 10";
        $list = $this->db->sql_select($query, $permalink);
        break;

    case "typology":
        $typologyID = $config["typology_data"];
        $query = "SELECT $standard_fields FROM ct_contents WHERE ($constraints_sql) AND typologyID = ? ORDER BY ct_contents.contentID DESC LIMIT 10";
        $list = $this->db->sql_select($query, $typologyID);
        break;

    case "random":
        $query = "SELECT $standard_fields FROM ct_contents WHERE (standalone = 1) AND ($constraints_sql) ORDER BY RAND() DESC LIMIT 10";
        $list = $this->db->sql_select($query);
        break;

    case "courses":
        $query = "SELECT $standard_fields FROM ct_contents WHERE (isCourse = 1) AND ($constraints_sql) ORDER BY RAND() DESC LIMIT 10";
        $list = $this->db->sql_select($query);
        break;

}





foreach ($list as $i=>$l) {
    if ($list[$i]["image"] !== null) {
        $list[$i]["image"] = $this->config["s3"]["origin_endpoint"] . "/" . $this->config["s3"]["prefix"] . "/c/640x360/" . $l["permalink"] . ".jpg";
    }

    if ($l["isCourse"] == 1) {

        //$course_contents = $this->db->sql_select("SELECT contentID FROM co_course_contents WHERE courseID = ?",  $l["contentID"]);
        $list[$i]["progress"] = 0;
        

    } else {
        $list[$i]["position"] = 0; // $this->run("frontend/userActions/getMediaPosition",["permalink" => $l["permalink"]])["position"];
    }

    $list[$i]["meta"] = json_decode($list[$i]["meta"], true);

    $list[$i]["authors"] = array_map(function($x) { return $x["authorID"]; } ,$this->db->sql_select("SELECT authorID FROM ct_content_authors WHERE permalink = ?",$l["permalink"]));

    unset($list[$i]["contentID"]);

    if (count($list) == 1) {
        $list[$i]["tags"] = $this->db->sql_select("SELECT ct_tags.tagID, ct_tags.description FROM ct_content_tags JOIN ct_tags ON ct_content_tags.tagID = ct_tags.tagID WHERE permalink = ?", $l["permalink"]);
    }

}

return [
    "title" => $widget[0]["title"],
    "contents" => $list,
    "_server" => $_SERVER,
    "redirect" => [
        "host" => "dev.terrapark.it"//"www.riacademy.it",
    ],
];