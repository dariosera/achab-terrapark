<?php


$this->db->delete("ua_favorites",[
    "permalink" => $d["permalink"],
    "AND" => [
        "IDutente" => $this->user["IDutente"]
    ] 
]);

//$this->run("frontend/public/evlogger",["eventCategory"=>"userLists","eventAction"=>"removeFromFavorites","eventValue"=>$d["permalink"]]);


return ["ok" => true];