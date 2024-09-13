<?php

$duration = 5; // Durata dei link s3

if (isset($d["media"])) {
    $media = json_decode($d["media"], true);
} else {
    $content = $this->db->sql_select("SELECT media FROM ct_contents WHERE permalink = ?",$d["permalink"]);
    $media = json_decode($content[0]["media"],true);
}

$out = [];

if (isset($media["mediaType"]) && count(array_keys($media[$media["mediaType"]."_data"])) > 0) {

    $out["mediaType"] = $media["mediaType"];

    if ($media["mediaType"] == "pdf") {
        $out["pdf_data"] = [
            "url" => $this->run("core/s3/get", ["key" => "documents/".$d["permalink"].".pdf", "duration" => $duration])["url"],
            "expire" => (new DateTime())->modify("+$duration minutes")->format(DateTime::ATOM),
        ];
    }

    if ($media["mediaType"] == "audio") {
        $out["audio_data"] = [
            "url" => $this->run("core/s3/get", ["key" => "audio/".$d["permalink"].".mp3", "duration" => $duration])["url"],
            "expire" => (new DateTime())->modify("+$duration minutes")->format(DateTime::ATOM),
        ];
    }

    if ($media["mediaType"] == "vimeo") {
        $out["vimeo_data"] = [
            "video_id" => $media["vimeo_data"]["video_id"],
        ];
    }

    if ($media["mediaType"] == "embed") {
        $out["embed_data"] = [
            "url" => $media["embed_data"]["url"],
        ];
    }


}

return $out;