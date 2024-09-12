<?php

return $this->db->sql_select("SELECT ct_contents.* FROM co_related_contents JOIN ct_contents ON ct_contents.permalink = co_related_contents.permalink WHERE co_related_contents.course = ?", $d["course"]);