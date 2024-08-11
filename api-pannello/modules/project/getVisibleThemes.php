<?php

return $this->db->sql_select("SELECT * FROM pr_visible_themes JOIN ct_themes ON ct_themes.themeID = pr_visible_themes.themeID WHERE projectID = ?", $d["projectID"]);