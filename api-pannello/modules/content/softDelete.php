<?php

$this->db->delete("co_course_contents",["contentID" =>$d["contentID"]], null);

$this->db->update("ct_contents",["deleted" => "1"], ["permalink" => $d["permalink"]]);

return ["ok" => true];