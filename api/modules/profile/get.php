<?php

$user = $this->db->sql_select("SELECT aa_utenti.IDutente, aa_utenti.nome, cognome, email, telefono, anno_nascita, comune, it_comuni.nome AS cerca_comune, professione, indirizzo, codice_fiscale FROM aa_utenti LEFT JOIN aa_informazioni_extra ON aa_utenti.IDutente = aa_informazioni_extra.IDutente LEFT JOIN it_comuni ON aa_informazioni_extra.comune = it_comuni.codice_catastale WHERE aa_utenti.IDutente = ?",$this->user["IDutente"]);

$email = $user[0]["email"];
$user[0]["profile_signature"] = $email . "::" .hash_hmac('md5', $user[0]["IDutente"] , "99eb53304b");

unset($user[0]["IDutente"]);

return $user[0];