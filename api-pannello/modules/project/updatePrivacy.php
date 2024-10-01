<?php

$this->db->update("pr_projects",[
    "privacy" => $d["privacy"],
],["projectID" => $d["projectID"]]);

return ["ok" =>true];