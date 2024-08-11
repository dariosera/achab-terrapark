<?php

// Ottengo numero pagine
$pdf = file_get_contents($d["files"][0]["tmp_name"]); 
$page_count = preg_match_all("/\/Page\W/", $pdf, $dummy); 


$old_meta = $this->db->sql_select("SELECT meta FROM ct_contents WHERE permalink = ?",$d["data"]["permalink"]);
if ($old_meta[0]["meta"] !== null) {
    $new_meta = json_decode($old_meta[0]["meta"], true);
} else {
    $new_meta = [];
}

$new_meta["pages"] = $page_count;

$this->db->update("ct_contents",[
    "meta" => $new_meta
],["permalink" => $d["data"]["permalink"]]);

$s3 = $this->run("core/s3/upload",[
    "file" => $d["files"][0],
    "key" => "documents/".$d["data"]["permalink"].".pdf",
    "private" => true,
]);

return [
    "s3" => $s3,
    "file_id" => $d["files"][0]["internal_id"]
];