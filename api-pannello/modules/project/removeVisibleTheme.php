<?php

$this->db->delete("pr_visible_themes",[
    "themeID" => $d["themeID"],
    "AND" => [
        "projectID" => $d["projectID"],
    ]
]);

return $this->run("project/getVisibleThemes",["projectID" => $d["projectID"]]);