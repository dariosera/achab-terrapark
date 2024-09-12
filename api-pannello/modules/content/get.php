<?php

$list = $this->db->sql_select("SELECT * FROM ct_contents WHERE permalink = ?", $d["permalink"]);

if (count($list) !== 1) {
    return ["error" => "Contenuto non trovato"];
}

$list[0]["standalone"] = $list[0]["standalone"] == '0' ? false : true;

if ($list[0]["image"] !== null) {
    $list[0]["image_url"] = $this->config["s3"]["origin_endpoint"] . "/" . $this->config["s3"]["prefix"] . "/c/640x360/" . $d["permalink"] . ".jpg";
}

$list[0]["authors"] = $this->run("content/getAuthors", ["permalink" => $list[0]["permalink"]]);

$list[0]["previews"] = [];
$media = json_decode($list[0]["media"], true);
if (isset($media["mediaType"]) && count(array_keys($media[$media["mediaType"]."_data"])) > 0) {

    if ($media["mediaType"] == "pdf") {
        $list[0]["previews"] = [
            "pdf" => $this->run("core/s3/get", ["key" => "documents/".$d["permalink"].".pdf", "duration" => 5])["url"],
        ];
    }

    if ($media["mediaType"] == "audio") {
        $list[0]["previews"] = [
            "audio" => $this->run("core/s3/get", ["key" => "audio/".$d["permalink"].".mp3", "duration" => 5])["url"],
        ];
    }

}

$list[0]["tags"] = $this->run("content/getTags", ["permalink" => $list[0]["permalink"]]);



return $list[0];