<?php

$c = $this->db->sql_select("SELECT * FROM ua_history WHERE IDutente = ? AND permalink = ?", $this->user["IDutente"], $d["permalink"]);



if (count($c) === 1) {
    $this->db->update("ua_history",[
        "updated_at" => "CURRENT_TIMESTAMP"
    ],[
        "IDutente" => $this->user["IDutente"],
        "AND" => [
            "permalink" => $d["permalink"]
        ]
    ]);
} else {
    $this->db->insertInto("ua_history",[
        "IDutente" => $this->user["IDutente"],
        "permalink" => $d["permalink"],
        "updated_at" => "CURRENT_TIMESTAMP",
    ]);
}

return ["ok" => true];