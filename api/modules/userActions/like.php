<?php

// Eseguo il removeDislike
$this->run("userActions/removeDislike",["permalink" => $d["permalink"]]);

$this->db->insertInto("ua_likes",[
    "permalink" => $d["permalink"],
    "IDutente" => $this->user["IDutente"],
]);

$count = $this->db->sql_select("SELECT COUNT(IDutente) AS n FROM ua_likes WHERE permalink = ?",$d["permalink"]);

//$this->run("public/evlogger",["eventCategory"=>"userEngagement","eventAction"=>"like","eventValue"=>$d["permalink"]]);

return [
    "nlikes" => $count[0]["n"]
];