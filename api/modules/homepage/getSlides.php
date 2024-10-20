<?php

$pro = $this->run("frontend/public/projectInfo");

$slides = $this->db->sql_select("SELECT * FROM pr_visible_slides JOIN hp_slides ON hp_slides.slideID = pr_visible_slides.slideID WHERE projectID = ?",$pro["projectID"]);


foreach ($slides as $i=>$s) {
    $slides[$i]["image_url"] = $this->config["s3"]["origin_endpoint"] . "/" . $this->config["s3"]["prefix"] . "/hps/1280x720/" . $s["slideID"] . ".jpg";
}


return $slides;