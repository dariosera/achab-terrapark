<?php

if ($d["sliderID"] === "__history__") {
    return $this->run("frontend/contents/search", ["target" => "history"]);
}

if ($d["sliderID"] === "__myCourses__") {
    return $this->run("frontend/contents/search", ["target" => "myCourses"]);
}

if ($d["sliderID"] === "__courses__") {
    return $this->run("frontend/contents/search", ["target" => "courses"]);
}

/// __author:1__
if (strpos($d["sliderID"],"__author") === 0) {
    $authorID = intval(
        explode("_",
            explode(":",$d["sliderID"])[1]
        )[0]
    );

    return $this->run("frontend/contents/search", ["target" => "author", "authorID" => $authorID]);

}

return $this->run("frontend/contents/search", ["target" => "boh"]);