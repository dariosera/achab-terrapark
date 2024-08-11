<?php

$this->db->delete("ua_likes",[
    "permalink" => $d["permalink"],
    "AND" => [ "IDutente" => $this->user["IDutente"]]
]);

$count = $this->db->sql_select("SELECT COUNT(IDutente) AS n FROM ua_likes WHERE permalink = ?",$d["permalink"]);

return [
    "nlikes" => $count[0]["n"]
];