<?php

$list = $this->db->sql_select("SELECT authorID, name, surname, role, bio, bibliography, s3_key FROM ct_authors LEFT JOIN up_files ON ct_authors.picture = up_files.file_id WHERE authorID = ?", $d["authorID"]);

foreach ($list as $i=>$l) {

    // Ottengo link immagine autore
    $list[$i]["picture_url"] = $this->config["s3"]["origin_endpoint"] . "/" . $this->config["s3"]["prefix"] . "/" . $list[$i]["s3_key"];


    // Ottengo tag contenuti associati ad autore

    $list[$i]["tags"] = $this->db->sql_select("SELECT DISTINCT ct_tags.tagID, ct_tags.description FROM ct_contents JOIN ct_content_authors ON ct_contents.permalink = ct_content_authors.permalink JOIN ct_content_tags ON ct_content_tags.permalink = ct_contents.permalink JOIN ct_tags ON ct_content_tags.tagID = ct_tags.tagID WHERE ct_content_authors.authorID = ?", $d["authorID"]);

}

return $list[0];