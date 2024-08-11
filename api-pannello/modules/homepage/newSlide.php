<?php

$newID = $this->db->insertInto("hp_slides",[
    "title" => "-",
    "content" => "-",
    "link_text" => "-",
    "link_href" => "-",
    "image" => null,
    "customerID" => null
]);

return ["slideID" => $newID];