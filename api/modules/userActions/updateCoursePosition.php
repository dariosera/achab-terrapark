<?php


$this->db->delete("ua_course_position",
 [  
     "IDutente" => $this->user["IDutente"],
     "AND" => [
         "course_permalink" => $d["course_permalink"]
     ]
 ]);


$this->db->insertInto("ua_course_position",[
    "IDutente" => $this->user["IDutente"],
    "permalink" => $d["permalink"],
    "course_permalink" => $d["course_permalink"]
]);

return ["ok" => true];