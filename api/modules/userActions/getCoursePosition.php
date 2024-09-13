<?php

return $this->db->sql_select("SELECT permalink FROM ua_course_position WHERE IDutente = ? AND course_permalink = ?", $this->user["IDutente"], $d["course_permalink"]);