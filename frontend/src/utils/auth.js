//import { loadPreferences, clearPreferences } from "./preferences";
export const AFTER_LOGIN_REDIRECT = "terrapark_after_login_redirect";
const jwt_token_key = "terrapark_jwt";

export function isLogged() {
    if (localStorage.getItem(jwt_token_key) === null) {
       return false;
    } else {
       return true;
    }
}

export function setToken(token) {
    localStorage.setItem(jwt_token_key,token);
    //clearPreferences();
   //loadPreferences()
    
}

export function getToken(token) {
    return localStorage.getItem(jwt_token_key);
}


export function logout() {
    localStorage.removeItem(jwt_token_key);
    window.location.replace("/")
    //clearPreferences();
}
