<?php

return $this->db->sql_select("SELECT authorID, name, surname, role FROM ct_authors");