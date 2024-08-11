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
$constraints_sql = implode(" AND ",$constraints);


$list = $this->db->sql_select("SELECT contentID, permalink, title, description, language, topicID, typologyID, isCourse, image, meta FROM ct_contents WHERE (isCourse = 1 OR standalone = 1) AND ($constraints_sql)");


foreach ($list as $i=>$l) {
    if ($list[$i]["image"] !== null) {
        $list[$i]["image"] = $this->config["s3"]["origin_endpoint"] . "/" . $this->config["s3"]["prefix"] . "/c/640x360/" . $l["permalink"] . ".jpg";
    }
    $list[$i]["meta"] = json_decode($list[$i]["meta"], true);
    unset($list[$i]["contentID"]);
}

return [
    "title" => $widget[0]["title"],
    "contents" => $list,
    "_server" => $_SERVER,
];