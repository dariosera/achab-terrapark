<?php


// Cerco contenuto tramite permalink

$con = $this->db->sql_select("SELECT contentID FROM ct_contents WHERE permalink = ? AND (isCourse = 1 OR standalone = 1) AND customerID IS NOT NULL", $d["permalink"]);

if (count($con) !== 1) {
    return ["error" => "Il permalink inserito non è valido"];
}

$this->db->insertInto("pr_visible_contents",[
    "contentID" => $con[0]["contentID"],
    "projectID" => $d["projectID"],
]);

return $this->run("project/getVisibleContents",["projectID" => $d["projectID"]]);