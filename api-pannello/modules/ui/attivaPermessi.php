<?php

$auth_domains = [
    "achabgroup.it",
    "agamus.it"
];

$auth_emails = ["mario.guidolin@sera.im"];

// Verifico che non li abbia già
if ($this->gruppo("Achab")) {
    return ["error" => "Fai già parte del gruppo 'Achab'"];
}

// Controllo il dominio
$u = $this->db->sql_select("SELECT email, password FROM aa_utenti WHERE IDutente = ?", $this->user["IDutente"]);

if (count($u) !== 1) {
    return ["error" => "Utente non trovato"];
}

// Controllo stato attivazione account
$v = $this->db->sql_select("SELECT IDverifica FROM zz_verifica_email WHERE IDutente = ?",$this->user["IDutente"]);

if (count($v) > 0) {
    return ["error" => "Non hai ancora verificato la tua email. Controlla la tua casella <b>".$u[0]["email"]."</b> e clicca il link di attivazione dell'account"];
}


$domain = explode("@",$u[0]["email"])[1];

if (in_array($domain,$auth_domains)) {
    
    if ($u[0]["password"] !== null) {
        return ["error" => "È necessario utilizzare l'autenticazione Single Sign On con il proprio account Google sul dominio $auth_domain"];
    }

} else {

    if (!in_array($u[0]["email"], $auth_emails)) {
        return ["error" => "Non sei idoneo all'attivazione."];
    }
    
}

// Se tutto ok, procedo.


// Trovo ID gruppo Achab
$g = $this->db->sql_select("SELECT IDgruppo FROM aa_gruppi WHERE gruppo = ?",'Achab');

if (count($g) !== 1) {
    return ["error" => "Si è verificato un errore. (1)"];
}

$this->db->insertInto("aa_appartenenza_gruppo",[
    "IDutente" => $this->user["IDutente"],
    "IDgruppo" => $g[0]["IDgruppo"]
]);

return ["refresh" => true];