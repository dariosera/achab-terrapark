<?php

$this->db->delete("ua_dislikes",[
    "permalink" => $d["permalink"],
    "AND" => [ "IDutente" => $this->user["IDutente"]]
]);


return [
   "ok" => true
];