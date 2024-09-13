<?php

$extra = $this->db->sql_select("SELECT * FROM aa_informazioni_extra WHERE IDutente = ?",$this->user["IDutente"]);

if (count($extra) === 0) {
    // Creo informazioni extra

    $this->db->insertInto("aa_informazioni_extra",[
        "IDutente" => $this->user["IDutente"],
        "comune" => $d["comune"],
        "anno_nascita" => $d["anno_nascita"],
        "professione" => $d["professione"],
        "indirizzo" => $d["indirizzo"],
        "codice_fiscale" => $d["codice_fiscale"],
        
    ]);

} else {
    $this->db->update("aa_informazioni_extra",[
        "comune" => $d["comune"],
        "anno_nascita" => $d["anno_nascita"],
        "professione" => $d["professione"],
        "indirizzo" => $d["indirizzo"],
        "codice_fiscale" => $d["codice_fiscale"],
    ],["IDutente" => $this->user["IDutente"]]);
}

$this->db->update("aa_utenti",[
    "telefono" => $d["telefono"],
],["IDutente" => $this->user["IDutente"]]);

return ["ok" => true];