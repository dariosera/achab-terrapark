<?php

return [
    "projects" => $this->db->sql_select("SELECT projectID, cu_customers.customerID, customer, title, start_date, end_date, domain, archived FROM pr_projects JOIN cu_customers ON cu_customers.customerID = pr_projects.customerID"),
    "archived" => 0,
];