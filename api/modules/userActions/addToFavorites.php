<?php

$this->db->insertInto("ua_favorites",[
    "permalink" => $d["permalink"],
    "IDutente" => $this->user["IDutente"],
]);

//$this->run("frontend/public/evlogger",["eventCategory"=>"userLists","eventAction"=>"addToFavorites","eventValue"=>$d["permalink"]]);


return ["ok" => true];