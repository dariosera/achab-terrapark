<?php

return $this->db->sql_select("SELECT ct_authors.* FROM ct_content_authors JOIN ct_authors ON ct_content_authors.authorID = ct_authors.authorID WHERE permalink = ?",$d["permalink"]);
