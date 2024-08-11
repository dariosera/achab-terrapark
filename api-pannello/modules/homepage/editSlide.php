<?php

$this->db->update("hp_slides",[
    "title" => $d["title"],
    "content" => $d["content"],
    "link_text" => $d["link_text"],
    "link_href" => $d["link_href"],
    "image" => $d["image"],
    "customerID" => isset($d["customerID"]) ? $d["customerID"] : null,
],["slideID" => $d["slideID"]]);

return ["ok" => true];
    
