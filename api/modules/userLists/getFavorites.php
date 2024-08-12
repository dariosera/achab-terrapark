<?php

$IDlista = $this->run("frontend/userLists/getFavoritesId");


if (isset($d["only_permalink"])) {
    return $this->db->sql_select("SELECT DISTINCT(permalink) FROM ul_contenuti WHERE IDlista = ?", $IDlista);
}

$contenuti = $this->db->sql_select("SELECT DISTINCT(cd_contenuti.permalink), cd_contenuti.IDcontenuto, titolo, descrizione, cd_contenuti.IDargomento, argomento, cd_contenuti.IDcategoria, cd_contenuti.immagine, tags, genere, display_order, nome_percorso AS tema, cd_percorsi.IDpercorso AS IDtema FROM ul_contenuti JOIN ud_unita_contenuti ON ul_contenuti.permalink = ud_unita_contenuti.permalink JOIN ud_unita ON ud_unita_contenuti.IDunita = ud_unita.IDunita JOIN cd_percorsi ON cd_percorsi.IDpercorso = ud_unita.IDpercorso JOIN cd_contenuti ON ul_contenuti.permalink = cd_contenuti.permalink JOIN cd_argomenti ON cd_contenuti.IDargomento = cd_argomenti.IDargomento WHERE IDlista = ? ORDER BY display_order", $IDlista);



foreach ($contenuti as $k=>$c) {
    $contenuti[$k]['target'] = $this->db->sql_select("SELECT IDtarget FROM cd_target_contenuti WHERE IDcontenuto = ?",$c['IDcontenuto']);
    unset($contenuti[$k]['IDcontenuto']);
}

$temi = [];

foreach ($contenuti as &$c) {
    if (!isset($temi[$c["IDtema"]])) {
        $temi[$c["IDtema"]] = [
            "text" => $c["tema"],
            "macrotema" => $c["IDargomento"],
            "contenuti" => [],
        ];
    }

    $temi[$c["IDtema"]]["contenuti"][] = $c;


}

return array_values($temi);