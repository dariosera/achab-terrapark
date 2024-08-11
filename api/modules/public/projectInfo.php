<?php

$pro = $this->db->sql_select("SELECT * FROM pr_projects JOIN cu_customers ON cu_customers.customerID = pr_projects.customerID WHERE projectID = ?", 1);

if (count($pro) !== 1) {
    return ["error" => "Progetto non trovato"];
}

$pro[0]["theme"] = json_decode($pro[0]["theme"], true);

return $pro[0];