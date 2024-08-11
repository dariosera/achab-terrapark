<?php 

$permalinks = [];

foreach ($d["permalinks"] as &$pl) {
    $permalinks[] = $this->db->con->quote($pl);
}
$permalinks = implode(",",$permalinks);

$contents = $this->db->sql_select("SELECT 
    p.permalink,
    COUNT(ua_likes.IDutente) AS nLikes,
    MAX(CASE WHEN ua_likes.IDutente = ? THEN 1 ELSE 0 END) AS isLiked,
    MAX(CASE WHEN ua_dislikes.IDutente = ? THEN 1 ELSE 0 END) AS isDisliked
FROM 
    (SELECT permalink FROM ct_contents WHERE permalink IN ($permalinks)) AS p
LEFT JOIN 
    ua_likes ON p.permalink = ua_likes.permalink
LEFT JOIN 
    ua_dislikes ON p.permalink = ua_dislikes.permalink
GROUP BY 
    p.permalink", $this->user["IDutente"], $this->user["IDutente"]);


$out = [];

foreach ($contents as &$c) {
    $s = [
        "active" => false,
        "liked" => false,
        "nLikes" => $c["nLikes"],
    ];
    if ($c["isLiked"] || $c["isDisliked"]) {
        $s["active"] = true;
    }
    if ($c["isLiked"]) {
        $s["liked"] = true;
    }

    $out[] = [
        "permalink" => $c["permalink"],
        "status" => $s,
    ];
}

return $out;