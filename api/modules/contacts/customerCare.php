<?php

$url = "https://api.clickup.com/api/v2/list/901205002281/task";

$user = $this->db->sql_select("SELECT nome, cognome, email FROM aa_utenti WHERE IDutente = ?", $this->user["IDutente"]);

$now = round(microtime(true) * 1000);

$day_of_week = date('N');

// Check if today is Friday or near the weekend, and adjust the due date accordingly
if ($day_of_week == 5) {
    // If today is Friday, add 3 days (to skip Saturday and Sunday) in milliseconds
    $due_date = $now + (3 * 24 * 60 * 60 * 1000);
} elseif ($day_of_week == 6) {
    // If today is Saturday, add 2 days (to skip Sunday)
    $due_date = $now + (2 * 24 * 60 * 60 * 1000);
} elseif ($day_of_week == 7) {
    // If today is Sunday, add 1 day (to move to Monday)
    $due_date = $now + (24 * 60 * 60 * 1000);
} else {
    // Otherwise, just add 24 hours
    $due_date = $now + (24 * 60 * 60 * 1000);
}

$params = [
    "name" => "Richiesta da ".$user[0]["email"],
    "description" => $d["message"],
    "custom_fields" => [
        "d7872ab4-6cc2-4494-a374-064b6ea3e040" => $user[0]["email"],
    ],
    "assignees" => [36343621],
    "due_date" => $due_date,
    "start_date" => $now,
];

clickupreq("POST",$url,$params,$this->config["clickup"]["token"]);

return ["ok" => true];

function clickupreq($method,$url,$params,$token) {
    $c = curl_init();
    curl_setopt($c, CURLOPT_URL, $url);
    curl_setopt($c, CURLOPT_CUSTOMREQUEST, $method);    
    if (count($params) > 0)                                                                 
        curl_setopt($c, CURLOPT_POSTFIELDS, json_encode($params));
    curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($c, CURLOPT_TIMEOUT, 5);
    curl_setopt($c, CURLOPT_HTTPHEADER, array(
        "Authorization: ".$token,
        'Content-Type: application/json',
    ));        
    
    $content = curl_exec($c);
    $http_status = curl_getinfo($c, CURLINFO_HTTP_CODE);
    
    if ($http_status == 200) {
        $out = json_decode($content,true);
    } else {
        $out = ["error" => $content];
    }
    
    curl_close($c);
    
    return $out;
}