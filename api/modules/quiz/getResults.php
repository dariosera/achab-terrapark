<?php

$response = $this->db->sql_select("SELECT * FROM qz_quiz_responses WHERE IDutente = ? AND responseID = ?",$this->user["IDutente"],$d["responseID"]);

$correct = json_decode($response[0]["correct_answers"], true);

$user = $d["answers"];

$errors = 0;
$wrong = [];
foreach ($correct as $i=>$q) {
    if ($q !== $user[$i]) {
        $errors++;
        $wrong[] = $i;
    }
}

return [
    "errors" => $errors,
    "correct" => $correct,
    "wrong" => $wrong,
];