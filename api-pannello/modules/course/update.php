<?php

$this->db->update("ct_contents",[
    "title" => $d["title"],
    "topicID" =>  !isset($d["topicID"]) ? null : $d["topicID"],
    "themeID" =>  !isset($d["themeID"]) ? null : $d["themeID"],
    "typologyID" => !isset($d["typologyID"]) ? null : $d["typologyID"],
    "description" => $d["description"],
    "language" => !isset($d["language"]) ? null : $d["language"],
    "authorID" => !isset($d["authorID"]) ? null : $d["authorID"],
    "standalone" => $d["standalone"] === true ? '1' : '0',
    "image" => $d["image"],
    "draft" => $d["draft"],
    "customerID" => !isset($d["customerID"]) ? null : $d["customerID"],
],["permalink" => $d["permalink"]]);

return ["ok" => true];