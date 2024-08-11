<?php

return $this->db->sql_select("SELECT slideID, title, content, link_text, link_href, customerID FROM hp_slides WHERE customerID IS NULL or customerID LIKE ?", $d["customerID"]);