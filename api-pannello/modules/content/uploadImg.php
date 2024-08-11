<?php

use \Gumlet\ImageResize;


$original_image = $d["files"][0]["tmp_name"];
list($width, $height, $type, $attr) = getimagesize($original_image);

// Check image ratio
$ratio = $width / $height;
$expected_ratio = 16 / 9;

if (abs($expected_ratio - $ratio) > 0.05) {
   // return ["error" => "L'immagine non ha le proporzioni corrette (16/9)"];
}

// Check size
$min_width = 1280;
if ($width < $min_width) {
   // return ["error" => "L'immagine non è abbastanza grande (min-width: 1280px)"];
}


//
$sizes = [
    [160,90],
    [640,360],
    [1280,720]
];

foreach ($sizes as &$size) {
    $image = new ImageResize($original_image);
    $image->resizeToBestFit($size[0], $size[1]);
    
    $scaled_img = $original_image."_".$size[0]."x".$size[1];
    
    $image->save($scaled_img);

    $file = $d["files"][0]["tmp_name"] = $scaled_img;

    $s3 = $this->run("core/s3/upload",[
        "file" => $d["files"][0],
        "key" => "c/{$size[0]}x{$size[1]}/".$d["data"]["permalink"].".jpg",
        "private" => false,
    ]);
    

}


return [
    "s3" => $s3,
    "file_id" => $d["files"][0]["internal_id"]
];