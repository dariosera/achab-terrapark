<?php

if (isset($d["media"]) && count($d["media"]) > 0 && isset($d["media"]["mediaType"]) && count(array_keys($d["media"][$d["media"]["mediaType"]."_data"])) > 0) {
    $media = $d["media"];
    $media_data = $media[$media["mediaType"]."_data"];
    $media_new = [
        "mediaType" => $media["mediaType"]
    ];
    $media_new[$media["mediaType"]."_data"] = $media[$media["mediaType"]."_data"];

    if ($media["mediaType"] == "vimeo") {
        // Ottengo durata
        $this->run("vimeo/updateDuration", ["permalink" => $d["permalink"], "video_id" => $media[$media["mediaType"]."_data"]["video_id"]]);
    }

} else {
    $media_new = "{}";
}



$this->db->update("ct_contents",[
    "title" => $d["title"],
    "themeID" => !isset($d["themeID"]) ? null : $d["themeID"],
    "topicID" => !isset($d["topicID"]) ? null : $d["topicID"],
    "typologyID" => !isset($d["typologyID"]) ? null : $d["typologyID"],
    "description" => $d["description"],
    "language" => !isset($d["language"]) ? null : $d["language"],
    "authorID" => !isset($d["authorID"]) ? null : $d["authorID"],
    "standalone" => $d["standalone"] === true ? '1' : '0',
    "image" => $d["image"],
    "media" => $media_new,
    "draft" => $d["draft"],
    "customerID" => !isset($d["customerID"]) ? null : $d["customerID"],
],["permalink" => $d["permalink"]]);

return ["ok" => true];