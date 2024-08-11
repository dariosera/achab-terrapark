<?php

// $pr = $this->db->sql_select("SELECT * FROM ac_progetti WHERE IDcliente = ?", $d["IDcliente"]);
// if (count($pr) > 0) {
//     return ["error" => "Impossibile eliminare il cliente. Ci sono ".count($pr)." progetti associati a questo cliente."];
// }

if ($this->db->delete("cu_customers",["customerID" => $d["customerID"]]) === false) {
    return ["error" => "Impossibile eliminare il cliente"];
}

return ["ok" => true];