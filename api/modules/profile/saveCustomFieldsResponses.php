<?php

foreach ($d["customSignupFields"] as $id=>$v) {
    $this->db->insertInto("pr_custom_signup_fields_responses",[
        "IDutente" => $this->user["IDutente"],
        "fieldID" => $id,
        "response" => $v,
    ]);
}

return ["ok" => true];