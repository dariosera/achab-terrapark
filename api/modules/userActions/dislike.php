<?php

// Eseguo removeLike
$this->run("frontend/userActions/removeLike",["permalink" => $d["permalink"]]);


$this->db->insertInto("ua_dislikes",[
    "permalink" => $d["permalink"],
    "IDutente" => $this->user["IDutente"],
]);

//$this->run("frontend/public/evlogger",["eventCategory"=>"userEngagement","eventAction"=>"dislike","eventValue"=>$d["permalink"]]);

return [
    "ok" => true
];