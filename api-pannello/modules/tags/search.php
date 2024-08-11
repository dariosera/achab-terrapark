<?php

$s = "%".$d["s"]."%";

return $this->db->sql_select("SELECT tagID as id, description as text FROM ct_tags WHERE description LIKE ?",$s);