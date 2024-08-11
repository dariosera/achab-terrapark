<?php

$this->db->insertInto("pr_visible_themes",[
    "themeID" => $d["themeID"],
    "projectID" => $d["projectID"],
]);

return $this->run("project/getVisibleThemes",["projectID" => $d["projectID"]]);