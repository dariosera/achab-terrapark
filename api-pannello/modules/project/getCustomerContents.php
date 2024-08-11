<?php

return [
    "standalone" => $this->db->sql_select("SELECT COUNT(contentID) AS n FROM ct_contents WHERE customerID = ? AND standalone = 1", $d["customerID"]),
    "courses" => $this->db->sql_select("SELECT COUNT(contentID) AS n FROM ct_contents WHERE customerID = ? AND isCourse = 1", $d["customerID"]),
];