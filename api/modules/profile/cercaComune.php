<?php

return $this->db->sql_select("SELECT nome, codice_catastale FROM it_comuni WHERE nome LIKE ?","%".$d["s"]."%");