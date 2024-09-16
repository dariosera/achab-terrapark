<?php

function randomPermalink($length = 8) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}


$permalink = randomPermalink();
$id = $this->db->insertInto("ct_contents",[
    "permalink" => $permalink,
    "media" => '{}',
    // "title" => $d["title"],
    // "topicID" => $d["topicID"],
    // "typologyID" => $d["typologyID"],
    // "description" => $d["description"],
    // "language" => !isset($d["language"]) ? null : $d["language"],
    // "authorID" => !isset($d["authorID"]) ? null : $d["authorID"],
    // "standalone" => $d["standalone"] === true ? '1' : '0',
    // "image" => $d["image"],
]);

return ["permalink" => $permalink, "contentID" => $id];