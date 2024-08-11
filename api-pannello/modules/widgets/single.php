<?php

$w = $this->db->sql_select("SELECT * FROM wi_widgets WHERE widgetID= ?",$d["widgetID"]);

if (count($w) !== 1) {
    return ["error" => "Widget non trovato"];
}

$w[0]["config"] = json_decode($w[0]["config"], true);

return $w[0];