<?php

$exact_match = $this->db->sql_select("SELECT customerID as id, customer as text FROM cu_customers WHERE customerID = ?",$d["s"]);

if (count($exact_match) === 1) {
    return $exact_match;
} else {
    $s = "%".$d["s"]."%";
    return $this->db->sql_select("SELECT customerID as id, customer as text FROM cu_customers WHERE customer LIKE ?",$s);
}
