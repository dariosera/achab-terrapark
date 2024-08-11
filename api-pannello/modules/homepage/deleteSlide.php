<?php

$this->db->delete("hp_slides",["slideID" => $d["slideID"]]);

return ["ok" => true];