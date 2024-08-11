<?php

if ($this->db->insertInto("cu_customers", [
    "customer" => $d["customer"]
]) === false) {
    return ["error" => "Impossibile aggiungere il cliente"];
} else {
    return ["ok" => true];
}