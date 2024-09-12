<?php

$this->db->delete("co_related_contents",[
    "course" => $d["course"],
    "AND" => [
        "permalink" => $d["permalink"],
    ]
]);

return ["ok" => true];