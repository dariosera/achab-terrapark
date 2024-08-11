<?php

if (count($this->db->sql_select("SELECT * FROM ua_opened_contents WHERE IDutente = ? AND permalink = ?", $this->user["IDutente"], $d["permalink"])) === 0) {
    $this->db->insertInto("ua_opened_contents",[
        "permalink" => $d["permalink"],
        "course_permalink" => $d["course_permalink"],
        "IDutente" => $this->user["IDutente"]
    ]);
}

return ["ok" => true];