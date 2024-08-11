<?php

$this->db->insertInto("ua_favorites",[
    "permalink" => $d["permalink"],
    "IDutente" => $this->user["IDutente"],
]);

//$this->run("public/evlogger",["eventCategory"=>"userLists","eventAction"=>"addToFavorites","eventValue"=>$d["permalink"]]);


return ["ok" => true];