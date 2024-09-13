<?php

$user = $this->db->sql_select("SELECT aa_utenti.IDutente, aa_utenti.nome, cognome, email, telefono, anno_nascita, comune, it_comuni.nome AS cerca_comune, professione, indirizzo, codice_fiscale FROM aa_utenti LEFT JOIN aa_informazioni_extra ON aa_utenti.IDutente = aa_informazioni_extra.IDutente LEFT JOIN it_comuni ON aa_informazioni_extra.comune = it_comuni.codice_catastale WHERE aa_utenti.IDutente = ?",$this->user["IDutente"]);

$empty_fields = [];
foreach ($user[0] as $k => $v) {
    if ($v == null) {
        $empty_fields[] = $k;
    }
}

if ($user[0]["anno_nascita"] == null || $user[0]["comune"] == null) {
    return [
        "action_required" => true,
        "empty_fields" => $empty_fields,
    ];
} else {
    return [
        "action_required" => false,
        "empty_fields" => $empty_fields,
    ];
}