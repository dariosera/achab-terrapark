<?php

return $this->db->sql_select("SELECT ct_tags.* FROM ct_content_tags JOIN ct_tags ON ct_content_tags.tagID = ct_tags.tagID WHERE permalink = ?", $d["permalink"]);