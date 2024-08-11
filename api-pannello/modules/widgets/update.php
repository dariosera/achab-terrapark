<?php

$config = $d["config"];
$config_clean = [];
$config_clean["mode"] = $config["mode"];
if (isset($config[$config_clean["mode"]."_data"])) {
    $config_clean[$config_clean["mode"]."_data"] = $config[$config_clean["mode"]."_data"];
}


if (isset($d["new"]) && $d["new"] === true) {
    $this->db->insertInto("wi_widgets",[
        "title" => $d["title"],
        "projectID" => $d["projectID"],
        "config" => $config_clean,
        "authorized_domains" => $d["authorized_domains"],
    ]);
} else {
    $this->db->update("wi_widgets",[
        "title" => $d["title"],
        "projectID" => $d["projectID"],
        "config" => $config_clean,
        "authorized_domains" => $d["authorized_domains"],
    ],["widgetID" => $d["widgetID"]]);
}



return ["ok" => true];