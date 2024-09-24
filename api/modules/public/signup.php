<?php

$result = $this->run("core/public/signup",$d);

if (isset($result["error"])) {
    return $result;
}

if ($result["ok"]) {

    if (count($d["customSignupFields"]) > 0) {
        // get user id

        $u = $this->db->sql_select("SELECT IDutente FROM aa_utenti WHERE email = ?",$d["email"]);

        if (count($u) !== 1) {
            return ["error" => "La registrazione non è andata a buon fine, riprovare."];
        }

        foreach ($d["customSignupFields"] as $id=>$v) {
            $this->db->insertInto("pr_custom_signup_fields_responses",[
                "IDutente" => $u[0]["IDutente"],
                "fieldID" => $id,
                "response" => $v,
            ]);
        }
    }

}