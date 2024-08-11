<?php

$old = $this->db->sql_select("SELECT meta FROM ct_contents WHERE permalink = ?",$d["permalink"]);

if ($old[0]["meta"] !== null) {
    $meta = json_decode($old[0]["meta"], true);
} else {
    $meta = [];
}

$meta["duration"] = $d["duration"];

$this->db->update("ct_contents",[
    "meta" => $meta
],["permalink" => $d["permalink"]]);

return ["meta" => $meta];