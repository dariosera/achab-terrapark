<?php

$list = $this->db->sql_select("SELECT * FROM hp_slides WHERE slideID =  ?", $d["slideID"]);

if (count($list) !== 1) {
    return ["error" => "Slide non trovata"];

}

return $list[0];