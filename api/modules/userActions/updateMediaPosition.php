<?php

if ($this->db->insertInto("ua_media_position",[
    "IDutente" => $this->user["IDutente"],
    "permalink" => $d["permalink"],
    "position" => $d["position"]
]) === false) {

    $this->db->update("ua_media_position",
    [
        "position" => $d["position"]
    ],
    [  
        "IDutente" => $this->user["IDutente"],
        "AND" => [
            "permalink" => $d["permalink"]
        ]
    ]);
}

return ["ok" => true];