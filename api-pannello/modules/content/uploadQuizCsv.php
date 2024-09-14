<?php

$s3 = $this->run("core/s3/upload",[
    "file" => $d["files"][0],
    "key" => "audio/".$d["data"]["permalink"].".mp3",
    "private" => true,
]);

return [
    "s3" => $s3,
    "file_id" => $d["files"][0]["internal_id"]
];