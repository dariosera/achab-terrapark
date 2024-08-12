<?php

if ($d["type"] == "contenuti") {
    $target = "favoritedStandalones";
} else {
    $target = "favoritedCourses";
}

return $this->run("frontend/contents/search", ["target" => $target]);