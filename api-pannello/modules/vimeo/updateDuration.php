<?php

$token = "569e781df09e099ffa68f2c852acd346";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://api.vimeo.com/videos/'.$d['video_id']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "Authorization: Bearer $token"
));

$res = curl_exec($ch);
$vimeo_data = json_decode($res,true);
curl_close($ch);

if (isset($vimeo_data["error"])) {
	return ["error" => "video_not_found"];
} else {
//	return ["duration" => $vimeo_data["duration"]];


	$old = $this->db->sql_select("SELECT meta FROM ct_contents WHERE permalink = ?",$d["permalink"]);

	if ($old[0]["meta"] !== null) {
		$meta = json_decode($old[0]["meta"], true);
	} else {
		$meta = [];
	}

	$meta["duration"] = $vimeo_data["duration"];

	$this->db->update("ct_contents",[
		"meta" => $meta
	],["permalink" => $d["permalink"]]);

	return ["ok" => true];
	

}