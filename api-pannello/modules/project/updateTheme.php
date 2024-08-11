<?php

$this->db->update("pr_projects",[
    "theme" => $d["theme"],
],["projectID" => $d["projectID"]]);

return ["ok" =>true];