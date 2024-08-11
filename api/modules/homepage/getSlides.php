<?php

$slides = $this->db->sql_select("SELECT * FROM hp_slides");


foreach ($slides as $i=>$s) {
    $slides[$i]["image_url"] = $this->config["s3"]["origin_endpoint"] . "/" . $this->config["s3"]["prefix"] . "/hps/1280x720/" . $s["slideID"] . ".jpg";
}


return $slides;