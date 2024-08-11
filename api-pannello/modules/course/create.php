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
$this->db->insertInto("ct_contents",[
    "permalink" => $permalink,
    "isCourse" => "1",
]);

return ["permalink" => $permalink];