<?php

$fields = [];

foreach (["title","start_date","end_date","cs_email","cs_phone","cs_hours"] as &$field) {
    $fields[$field] = $d[$field];
}

$this->db->update("pr_projects",$fields,["projectID" => $d["projectID"]]);

return ["ok" => true];