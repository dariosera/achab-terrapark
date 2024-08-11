<?php

if ($d["type"] == "contenuti") {
    $target = "favoritedStandalones";
} else {
    $target = "favoritedCourses";
}

return $this->run("contents/search", ["target" => $target]);