import axios from "axios";
import {CapacitorPersistentAccount} from "@capgo/capacitor-persistent-account";

 CapacitorPersistentAccount.readAccount()
     .then(account=>{

         if (account.data) {
             axios.defaults.headers.common['Authorization'] = "Bearer "+account.data.token;

         }
     })



axios.defaults.baseURL = 'http://localhost:8000/api';
axios.defaults.headers.common['Accept'] = "application/json";
axios.defaults.headers.post['Content-Type']="application/json";

window.axios=axios;