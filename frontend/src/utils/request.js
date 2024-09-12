import axios from 'axios';
import { isLogged, getToken, logout, AFTER_LOGIN_REDIRECT} from './auth';
import useToasts from '@/stores/toasts';

function fatalError(a,b) {
    useToasts().addToast({
        title : "Errore",
        content : `<i class="bi bi-exclamation-triangle"></i> TerraPark si è interrotto in modo anomalo.`,
        autohide: false
    })
}

export function request(obj) {

        //let uT = useToasts();    
        const toasts = useToasts();

        const axios_config = {
            // validateStatus: function (status) {
            //     return status < 500; // Resolve only if the status code is less than 500
            //   }
        };
        
        if (isLogged()) {
            axios_config["headers"] = {
                "Authorization" : `Bearer ${getToken()}`,
            }
        }

        if (!obj?.hideLoader === true)
            document.querySelector("body").classList.add("wait");

        axios.post(
            import.meta.env.VITE_API_ENDPOINT + "/?task=" + (obj.task.startsWith("core") ? obj.task : 'frontend/' + obj.task),
            obj.data,
            axios_config)
        .then(response => {

                if (response.headers['kadro-debugger']) {
                    console.log(`[KADRO-DEBUGGER] ${response.headers['kadro-debugger']}`)
                }
                
                

                if ("string" === typeof(response.data)) {
                    console.log(`[KADRO-DEBUGGER] ${response.data}`)
                    return;
                }
                
            
                if ("undefined" !== typeof(response.data.error)) {
                   
                    if ("undefined" !== typeof(obj.error)) {
                        obj.error(response.data);
                    } else {
                        toasts.addToast({
                            title : `<i class="bi bi-exclamation-triangle"></i> Errore`,
                            content : `${response.data.error}`,
                        })
                        console.error(`[KADRO-DEBUGGER] ${response.data.error}`)
                    }
                    
        
                } else {
                    obj.callback(response.data);
                }

            
        }).catch(function(error) {
            

            if ("undefined" !== typeof(obj.error)) {
                obj.error(error.response.data)
                return;
            }

            if (typeof(error?.response?.status) !== "undefined") {
                if (error?.response?.status === 401) {
                    sessionStorage.setItem(AFTER_LOGIN_REDIRECT,window.location.pathname);
                    logout()
                    return;
                }
    
                else if (error?.response?.status === 403) {
                    toasts.addToast({
                        title : "Ooops!",
                        content : "Non hai l'autorizzazione per eseguire questa azione.",
                        autohide: false
                    })
                    return; 
                }
    
               
                else if (error?.response?.status === 404) {
                    toasts.addToast({
                        title : "Ooops!",
                        content : "Si è verificato un errore (404)",
                        autohide: false
                    })
                    return; 
                } else {
                    
                    toasts.addToast({
                        title : "Ooops!",
                        content : "Si è verificato un errore (5xx)",
                        autohide: false
                    })
                    return; 
                    

                }
            } else {
                fatalError("Errore di rete",`<p>Non è stato possibile collegarsi al server remoto.</p><p>Potrebbe trattarsi di un problema temporaneo con la tua rete: ricarica la pagina tra qualche istante per ricollegarti.</p><p>Se il problema persiste, contatta l'assistenza.</p>`)
            }

            
            

            // if ("undefined" !== typeof(e.response.data.error)) {
            //     if ("undefined" === typeof(obj.error)) { 
            //         useToasts().addToast({
            //             title : "Si è verificato un errore",
            //             content : `${e.response.data.error}`,
            //         })
            //     } else {
                    
            //         obj.error(e?.response);
            //     }
            // } else {
            //     useToasts().addToast({
            //         title : "Si è verificato un errore",
            //         content : `${e?.response.data}`,
            //     })
            // }
        }).finally(() => {
            setTimeout(() => {
                document.querySelector("body").classList.remove("wait");
            },150)
        })
}