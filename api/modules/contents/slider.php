<?php

if ($d["sliderID"] === "__history__") {
    return $this->run("contents/search", ["target" => "history"]);
}

if ($d["sliderID"] === "__myCourses__") {
    return $this->run("contents/search", ["target" => "myCourses"]);
}

if ($d["sliderID"] === "__courses__") {
    return $this->run("contents/search", ["target" => "courses"]);
}

return $this->run("contents/search", ["target" => "boh"]);