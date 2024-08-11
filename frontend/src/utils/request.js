import axios from 'axios';
import { isLogged, getToken, logout} from './auth';

function fatalError(a,b) {
    alert(`${a} - ${b}`)
}

export function request(obj) {

        //let uT = useToasts();    

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
            import.meta.env.VITE_API_ENDPOINT + "/?task=" + obj.task,
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
                    alert("Auth_required")
                    logout()
                    return;
                }
    
                else if (error?.response?.status === 403) {
                    // uT.addToast({
                    //     title : "403 - Forbidden",
                    //     content : `Non sei autorizzato a svolgere questa azione.
                    //                 <hr>
                    //                 <i class="bi bi-exclamation-square"></i> <code>${obj.task}</code>
                    //                 `
    
                    // })
                    alert("not authorized")
                    return; 
                }
    
               
                else if (error?.response?.status === 404) {
                    // uT.addToast({
                    //     title : "404 - Not found",
                    //     content : `Non è stato possibile completare questa richiesta.
                    //     <hr>
                    //     <i class="bi bi-exclamation-square"></i> <code>${obj.task}</code>
                    //     `
    
                    // })
                    alert("404")
                    return; 
                } else {
                    
                    // uT.addToast({
                    //     title : "5XX - Network error",
                    //     content : "Impossibile contattare il server"
    
                    // })
                    alert("5XX")
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