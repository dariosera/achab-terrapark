<?php

use \Gumlet\ImageResize;

$original_image = $d["files"][0]["tmp_name"];

$image = new ImageResize($original_image);
$image->resizeToHeight(100);

$scaled_img = $original_image."_crop";
$image->save($scaled_img);

$file = $d["files"][0]["tmp_name"] = $scaled_img;

$s3 = $this->run("core/s3/upload",[
    "file" => $d["files"][0],
    "key" => "logo/extra-logos-".$d["data"]["projectID"].".png",
    "private" => false,
]);

return [
    "s3" => $s3,
    "file_id" => $d["files"][0]["internal_id"]
];