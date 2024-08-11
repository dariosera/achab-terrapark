<?php

$this->db->delete("ct_contents", ["permalink" => $d["permalink"]]);

return ["ok" => true];