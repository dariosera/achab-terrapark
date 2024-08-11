<?php

use \Gumlet\ImageResize;

$original_image = $d["files"][0]["tmp_name"];
$image = new ImageResize($original_image);
$image->crop(300, 300);
$scaled_img = $original_image."_300x300"; 
$image->save($scaled_img);
$file = $d["files"][0]["tmp_name"] = $scaled_img;


$s3 = $this->run("core/s3/upload",[
    "file" => $d["files"][0],
    "key" => "authors/".bin2hex(random_bytes(6)).".jpg",
    "private" => false,
]);

return [
    "s3" => $s3,
    "file_id" => $d["files"][0]["internal_id"]
];