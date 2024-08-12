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

/// __author:1__
if (strpos($d["sliderID"],"__author") === 0) {
    $authorID = intval(
        explode("_",
            explode(":",$d["sliderID"])[1]
        )[0]
    );

    return $this->run("contents/search", ["target" => "author", "authorID" => $authorID]);

}

return $this->run("contents/search", ["target" => "boh"]);