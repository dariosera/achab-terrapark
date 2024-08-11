<?php

return $this->db->sql_select("SELECT * FROM pr_visible_contents JOIN ct_contents ON pr_visible_contents.contentID = ct_contents.contentID WHERE projectID = ?", $d["projectID"]);