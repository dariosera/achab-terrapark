//import { loadPreferences, clearPreferences } from "./preferences";
export function isLogged() {
    if (localStorage.getItem("sspa_token") === null) {
       return false;
    } else {
       return true;
    }
}

export function setToken(token) {
    localStorage.setItem("sspa_token",token);
    //clearPreferences();
   //loadPreferences()
    
}

export function getToken(token) {
    return localStorage.getItem("sspa_token");
}


export function logout() {
    localStorage.removeItem("sspa_token");
    window.location.replace("/")
    //clearPreferences();
}
