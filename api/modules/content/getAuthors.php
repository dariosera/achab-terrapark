<?php

return array_map(function($x) { return $x["authorID"]; } ,$this->db->sql_select("SELECT authorID FROM ct_content_authors WHERE permalink = ?",$d["permalink"]));