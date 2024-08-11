<?php

$position = $this->db->sql_select("SELECT position FROM ua_media_position WHERE IDutente = ? AND permalink = ?",$this->user["IDutente"],$d["permalink"]);

if (count($position) === 0) {
    return ["position" => 0];
} else {
    return $position[0];
}